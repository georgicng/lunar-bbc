<?php

namespace App\Extensions\PaymentTypes;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Events\PaymentAttemptEvent;
use Lunar\Models\Contracts\Transaction as TransactionContract;
use Lunar\PaymentTypes\AbstractPayment;

class BankTransfer extends AbstractPayment
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): ?PaymentAuthorize
    {
        $address = $this->cart->billingAddress;
        if (! $this->order) {
            if (! $this->order = $this->cart->draftOrder()->first()) {
                $this->order = $this->cart->createOrder();
            }
        }
        $orderMeta = array_merge(
            (array) $this->order->meta,
            $this->data['meta'] ?? []
        );

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
            'driver' => 'bank-transfer',
            'success' => true,
            'reference' => $this->order->reference,
            'card_type' => 'na',
            'meta' => [ 'billingAddress' => $address]
        ]);

        $response = new PaymentAuthorize(
            success: true,
            orderId: $this->order->id,
            paymentType: 'transfer',
        );

        PaymentAttemptEvent::dispatch($response);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(TransactionContract $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        $this->order->update([
            'status' => 'payment-refunded',
        ]);

        $this->order->transactions()->create([
            'parent_transaction_id' => $transaction->id,
            'type' => 'refund',
            'amount' => $amount,
            'status' => 'success',
            'driver' => 'bank-transfer',
            'success' => true,
            'reference' => $transaction->reference,
            'card_type' => 'na'
        ]);
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(TransactionContract $transaction, $amount = 0): PaymentCapture
    {
        $this->order->update([
            'status' => 'payment-received',
        ]);

        $this->order->transactions()->create([
            'parent_transaction_id' => $transaction->id,
            'type' => 'capture',
            'amount' => $amount,
            'status' => 'success',
            'driver' => 'bank-transfer',
            'success' => true,
            'reference' => $transaction->reference,
            'card_type' => 'na'
        ]);
        return new PaymentCapture(true);
    }

    public function getData() {
        return $this->config;
    }
}
