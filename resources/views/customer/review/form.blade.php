@extends('layouts.cust')

@section('contents')
    <form action="{{ route('submit-review') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="order_id" class="form-label">Order ID</label>
            <select class="form-select" id="order_id" name="order_id">
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}" {{ $order->id == old('order_id') ? 'selected' : '' }}>
                        {{ $order->invoice_number }}
                    </option>
                @endforeach
            </select>
        </div>
        <input type="text" name="product_id" value="{{ $orderItem->product_id }}" hidden>
        <div class="mb-3">
            <label for="review" class="form-label">Review</label>
            @if ($orderItem->review)
                <p class="fw-bold">{{ $orderItem->review->review }}</p>
            @else
                <textarea class="form-control" id="review" name="review" rows="3"></textarea>
            @endif
        </div>
        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select class="form-select" id="rating" name="rating" {{ $orderItem->review ? 'disabled' : '' }}>
                <option value="1" {{ $orderItem->review && $orderItem->review->rating == 1 ? 'selected' : '' }}>1
                </option>
                <option value="2" {{ $orderItem->review && $orderItem->review->rating == 2 ? 'selected' : '' }}>2
                </option>
                <option value="3" {{ $orderItem->review && $orderItem->review->rating == 3 ? 'selected' : '' }}>3
                </option>
                <option value="4" {{ $orderItem->review && $orderItem->review->rating == 4 ? 'selected' : '' }}>4
                </option>
                <option value="5" {{ $orderItem->review && $orderItem->review->rating == 5 ? 'selected' : '' }}>5
                </option>
            </select>
        </div>
        @if (!$orderItem->review)
            <button type="submit" class="btn btn-primary">Submit Review</button>
        @endif
    </form>
@endsection
