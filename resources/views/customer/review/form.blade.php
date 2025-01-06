@extends('layouts.cust')

@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Submit Review</h1>
                    <div class="card">
                        <div class="card-body m-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Product</th>
                                        <th>Product Variant ID</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->order_item as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->order_id }}</td>
                                            <td>{{ $orderItem->product->name }}</td>
                                            <td>{{ $orderItem->product_variant->name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->quantity }}</td>
                                            <td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @if ($data->status == 'Diterima')
                                            <tr>
                                                <td colspan="6">
                                                    <form action="{{ route('submit-review') }}" method="post">
                                                        @csrf
                                                        <input type="text" name="order_id" value="{{ $data->id }}"
                                                            hidden>
                                                        <input type="text" name="product_id"
                                                            value="{{ $orderItem->product_id }}" hidden>
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
                                                            <select class="form-select" id="rating" name="rating"
                                                                {{ $orderItem->review ? 'disabled' : '' }}>
                                                                <option value="1"
                                                                    {{ $orderItem->review && $orderItem->review->rating == 1 ? 'selected' : '' }}>
                                                                    1</option>
                                                                <option value="2"
                                                                    {{ $orderItem->review && $orderItem->review->rating == 2 ? 'selected' : '' }}>
                                                                    2</option>
                                                                <option value="3"
                                                                    {{ $orderItem->review && $orderItem->review->rating == 3 ? 'selected' : '' }}>
                                                                    3</option>
                                                                <option value="4"
                                                                    {{ $orderItem->review && $orderItem->review->rating == 4 ? 'selected' : '' }}>
                                                                    4</option>
                                                                <option value="5"
                                                                    {{ $orderItem->review && $orderItem->review->rating == 5 ? 'selected' : '' }}>
                                                                    5</option>
                                                            </select>
                                                        </div>
                                                        @if (!$orderItem->review)
                                                            <button type="submit" class="btn btn-primary">Submit
                                                                Review</button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                        @else
                                            <span class="badge bg-warning text-dark mb-3"><i
                                                    class="bi bi-exclamation-triangle me-1"></i> Barang Belum
                                                diterima</span>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
