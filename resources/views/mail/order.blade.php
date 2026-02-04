<h1>It's on the way!</h1>

<p>Your order with reference {{ $order->reference }} has been dispatched!</p>

<p>{{ $order->total->formatted() }}</p>

@if($content ?? null)
    <h2>Additional notes</h2>
    <p>{{ $content }}</p>
@endif

@foreach($order->lines as $line)
    <!--  -->
@endforeach
