@extends('layouts.admin')
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
                                    <p>Resi</p>
                                </div>
                                <div class="col-md-6">
                                    <p>: {{ $order->invoice_number }}</p>
                                    <p>: {{ \Carbon\Carbon::parse($order->created_at)->locale('id_ID')->isoFormat('d F Y') }}
                                    </p>
                                    <p>: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    <p>: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                    <p>: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                    <p>: {{ $order->shipping_courier }}</p>
                                    <p>: {{ $order->user_address->address ?? 'N/A' }}</p>
                                    <p>: {{ $order->notes }}</p>
                                    <p>: {{ $order->status }}</p>
                                    <p>: {{ $order->shipping_tracking_number }}</p>
                                </div>
                            </div>
                            <div class="row">
                                @if ($order->status != 'Dibatalkan')
                                    <form action="{{ route('detailpesananorder-update', ['id' => $order->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-8">
                                                <label for="">Status</label>
                                                <select name="status" id="status" class="form-control"
                                                    onchange="showResiInput(this)">
                                                    <option value="">Choose Status</option>
                                                    <option value="sedang di proses">Sedang Di Proses</option>
                                                    <option value="dalam pengiriman">Dalam Pengiriman</option>
                                                </select>
                                                <div id="resi-input" style="display: none;">
                                                    <div class="form-group mt-3">
                                                        <label for="resi" class="mb-1">Resi</label>
                                                        <input type="text" class="form-control" id="resi"
                                                            name="resi">
                                                    </div>
                                                </div>
                                                <script>
                                                    function showResiInput(e) {
                                                        if (e.value === 'dalam pengiriman') {
                                                            document.getElementById('resi-input').style.display = 'block';
                                                        } else {
                                                            document.getElementById('resi-input').style.display = 'none';
                                                        }
                                                    }
                                                </script>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-primary my-4">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
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
                                        {{-- <th>Product Variant ID</th> --}}
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
                                            {{-- <td>{{ $orderItem->product_variant->name ?? 'N/A' }}</td> --}}
                                            <td>{{ $orderItem->quantity }}</td>
                                            <td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
                                        </tr>
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
                        {{-- <div class="card-head m-4">
                            <h3>Cek Pembayaran</h3>
                        </div> --}}
                        <div class="card-body my-4">
                            @if ($order->status != 'Dibatalkan')
                                <a href="{{ route('admin.payment.detail', $order->id) }}" class="btn btn-success">Cek
                                    Pembayan</a>
                            @else
                                <span class="badge bg-danger">Transaksi Dibatalkan</span>
                            @endif
                            {{-- @if ($order->status == 'Diterima')
                                <div class="col-lg-12">
                                    <div class="badge badge-info">Barang Sudah Diterima</div>
                                </div>
                            @else
                                <div class="col-lg-12">
                                    <p>Barang Dalam pengiriman</p>
                                    <a href="{{ route('barang.diterima', ['id' => $order->id]) }}"
                                        class="btn btn-success">Pesanan diterima</a>
                                </div>
                            @endif --}}
                            <a href="{{ route('admin.invoice-detail', ['id' => $order->id]) }}"
                                class="btn btn-primary">Invoice</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
