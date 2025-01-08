@extends('layouts.cust')

@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-head m-4">
                            <h3>Order NO {{ $order->invoice_number }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Invoice Number</p>
                                    <p>Order Date</p>
                                    <p>Subtotal</p>
                                    <p>Shipping Cost</p>
                                    <p>Total</p>
                                    <p>Shipping Courier</p>
                                    <p>Shipping Address</p>
                                    <p>Notes</p>
                                    <p>Status</p>
                                </div>
                                <div class="col-md-6">
                                    <p>: {{ $order->invoice_number }}</p>
                                    <p>: {{ $order->created_at }}</p>
                                    <p>: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    <p>: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                    <p>: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                    <p>: {{ $order->shipping_courier }}</p>
                                    <p>: {{ $order->user_address->address ?? 'N/A' }}</p>
                                    <p>: {{ $order->notes }}</p>
                                    <p>: {{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-head m-4">
                            <h3>Order Items</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Product Variant ID</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_item as $orderItem)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><img style="width: 100px"
                                                    src="{{ url($orderItem->product->images->where('is_primary', true)->first()->image_url) }}"
                                                    alt="Product Image"></td>
                                            <td>{{ $orderItem->product->name }}</td>
                                            <td>{{ $orderItem->product_variant->name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->quantity }}</td>
                                            <td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @if ($order->status == 'Diterima')
                                            <tr>
                                                <td colspan="7">
                                                    <form action="{{ route('submit-review') }}" method="post">
                                                        @csrf
                                                        <input type="text" name="order_id" value="{{ $order->id }}"
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
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-head m-4">
                            <h3>Recipient Data</h3>
                        </div>
                        <div class="card-body">
                            <p>Name : {{ $order->user->name }}</p>
                            <p>Email : {{ $order->user->email }}</p>
                            <p>Phone : {{ $order->user->phone }}</p>
                            <p>Address : {{ $order->user_address->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-head m-4">
                            <h3>Action Order</h3>
                        </div>
                        <div class="card-body">
                            @if ($order->status == 'Diterima')
                                <div class="col-lg-12">
                                    <div class="badge bg-info">Barang Sudah Diterima</div>
                                </div>
                            @else
                                <div class="col-lg-12">
                                    <p>Barang Dalam pengiriman</p>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#confirmReceivedModal">
                                        Pesanan diterima
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="confirmReceivedModal" tabindex="-1"
                                        aria-labelledby="confirmReceivedModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmReceivedModalLabel">Konfirmasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah anda yakin barang sudah diterima?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">back</button>
                                                    <a href="{{ route('barang.diterima', ['id' => $order->id]) }}"
                                                        class="btn btn-success">sudah</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
