<?php

namespace App\Extensions\PaymentTypes;

use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Events\PaymentAttemptEvent;
use Lunar\Models\Contracts\Transaction;
use Lunar\PaymentTypes\AbstractPayment;
use App\Lib;

class Paystack extends AbstractPayment
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): ?PaymentAuthorize
    {
        if (!$this->order) {
            if (!$this->order = $this->cart->order) {
                $this->order = $this->cart->createOrder();
            }
        }

        $status = $this->data['authorized'] ?? null;

        $this->order->update([
            'status' => $status ?? ($this->config['authorized'] ?? null),
            'meta' => $orderMeta,
            'placed_at' => now(),
        ]);

        $this->order->transactions()->create([
            'type' => 'intent',
            'amount' => $this->order->total,
            'status' => 'success',
            'driver' => 'paystack',
            'success' => true,
            'reference' => $this->order->reference,
        ]);

        $response = new PaymentAuthorize(
            success: true,
            message: 'The payment was successful',
            orderId: $this->order->id,
            paymentType: 'card'
        );

        PaymentAttemptEvent::dispatch($response);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        $this->order->update([
            'status' => 'payment-refunded',
        ]);

        $this->order->transactions()->create([
            'parent_transaction_id' => $transaction->id,
            'type' => 'refund',
            'amount' => $amount,
            'status' => 'success',
            'driver' => 'paystack',
            'success' => true,
            'reference' => $transaction->reference,
        ]);
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        if ($this->confirm()) {
            $this->order->update([
                'status' => 'payment-received',
            ]);

            $this->order->transactions()->create([
                'parent_transaction_id' => $transaction->id,
                'type' => 'capture',
                'amount' => $amount,
                'status' => 'success',
                'driver' => 'paystack',
                'success' => true,
                'reference' => $transaction->reference,
            ]);
            return new PaymentCapture(true);
        }
    }

    public function confirm($reference)
    {
        $model = new Lib\Paystack($this->data['url'], $this->data['secret']);
        return $model->isValid($reference);
    }
}
