@extends('layouts.admin')

@section('contents')
    <!-- Di bagian head atau sebelum closing body -->
    <section>
        <div class="container">
            <h2>Data Order</h2>
            <div class="row">
                <div class="col-lg-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Orders</h5>
                            <div class="mb-3">
                                <label for="orderStatusFilter" class="form-label">Filter Status Pesanan</label>
                                <select id="orderStatusFilter" class="form-select" onchange="filterOrders()">
                                    <option value="">Semua Status</option>
                                    <option value="Belum Dibayar">Menunggu Pembayaran</option>
                                    <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                    <option value="Pembayaran Diterima">Pembayaran Diterima</option>
                                    <option value="sedang di proses">Sedang Diproses</option>
                                    <option value="dalam pengiriman">Dalam Pengiriman (+Resi)</option>
                                    <option value="Pembayaran Gagal">Pembayaran Gagal</option>
                                    <option value="Diterima">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>



                            <!-- Table with stripped rows -->
                            <div class="table-responsive">

                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                No. Invoice
                                            </th>
                                            <th>customer</th>
                                            <th>status</th>
                                            <th>sub total</th>
                                            <th>shipping cost</th>
                                            <th>total</th>
                                            {{-- <th>shipping courier</th>
                                            <th>shipping address</th>
                                            <th>notes</th> --}}
                                            <th>date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr data-created-at="{{ $item->created_at }}">
                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('detailpesananorder', ['id' => $item->id]) }}">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    {{-- <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#orderItemsModal{{ $item->id }}">

                                                    </button> --}}
                                                </td>
                                                <td>{{ $item->invoice_number }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                                                {{-- <td>{{ $item->shipping_courier }}</td>
                                                <td>{{ $item->user_address ? $item->user_address->address : 'N/A' }}</td>
                                                <td>{{ $item->notes }}</td> --}}
                                                <td>{{ $item->created_at }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function filterOrders() {
                const filterValue = document.getElementById('orderStatusFilter').value;
                const rows = document.querySelectorAll('.datatable tbody tr');

                rows.forEach(row => {
                    const status = row.querySelector('td:nth-child(4)').innerText;
                    if (filterValue === "" || status === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>
        {{-- @foreach ($orders as $item)
            <div class="modal fade" id="orderItemsModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="orderItemsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderItemsModalLabel">Order Items for {{ $item->invoice_number }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset($item->payment_proof) }}" class="img-fluid" alt="Transfer Proof">
                            <div class="mt-3 d-flex justify-content-between">
                                <a href="{{ route('admin.order.approve', ['id' => $item->id]) }}"
                                    class="btn btn-success">Approve</a>
                                <a href="{{ route('admin.order.cancel', ['id' => $item->id]) }}"
                                    class="btn btn-danger">Cancel</a>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Product ID</th>
                                        <th>Product Variant ID</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->order_item as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->order_id }}</td>
                                            <td>{{ $orderItem->product_id }}</td>
                                            <td>{{ $orderItem->product_variant_id ?? 'N/A' }}</td>
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
            </div>
        @endforeach --}}
    </section>
@endsection
