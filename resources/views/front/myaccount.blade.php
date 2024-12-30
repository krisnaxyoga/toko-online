@extends('layouts.front')
@section('contents')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/cozas/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Profile
        </h2>
    </section>
    <section class="bg0 p-t-104 p-b-116">
        <div class="container">
            <div class="flex-w flex-tr justify-content-center">

                <div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                    <div class="flex-w w-full p-b-42">

                    </div>
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-user"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Name
                            </span>

                            <h3 class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->name }}
                            </h3>
                        </div>
                    </div>

                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-phone-handset"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Phone
                            </span>

                            <p class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->phone }}
                            </p>
                        </div>
                    </div>

                    <div class="flex-w w-full">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-envelope"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Email
                            </span>

                            <p class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-map-marker"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Address
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">

                                {{ auth()->user()->user_address->where('is_primary', 1)->first()->address }}
                            </p>
                            <p>{{ auth()->user()->user_address->where('is_primary', 1)->first()->province->name }}</p>
                            <p>{{ auth()->user()->user_address->where('is_primary', 1)->first()->city->name }}</p>
                        </div>
                    </div>
                    <div class="flex-w w-full">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
                <div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-cart"></span>
                        </span>
                        <span class="mtext-110 cl2">
                            Orders
                        </span>
                        <div class="size-212 p-t-2">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                No. Invoice
                                            </th>
                                            <th>status</th>
                                            <th>sub total</th>
                                            <th>shipping cost</th>
                                            <th>total</th>
                                            <th>shipping courier</th>
                                            <th>shipping address</th>
                                            <th>notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr>
                                                <td> <button type="button" class="btn btn-primary btn-sm mb-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#orderItemsModal{{ $item->id }}">
                                                        Show Items
                                                    </button>
                                                    <a href="{{ route('invoice', ['id' => $item->id]) }}"
                                                        class="btn btn-success btn-sm mb-2">
                                                        Invoice Detail
                                                    </a>
                                                </td>
                                                <td>{{ $item->invoice_number }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                                                <td>{{ $item->shipping_courier }}</td>
                                                <td>{{ $item->user_address->address ?? 'N/A' }}</td>
                                                <td>{{ $item->notes }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($orders as $item)
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
                            @if ($item->shipping_tracking_number != null)
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <p>Shipping Tracking Number: {{ $item->shipping_tracking_number }}</p>
                                        <p>cek resi disini </p>
                                        <a href="https://cekresi.com/" target="_blank" class="btn btn-info mb-3">Cek
                                            Resi</a>
                                    </div>
                                    @if ($item->status == 'Diterima')
                                        <div class="col-lg-12">
                                            <div class="badge badge-info">Barang Sudah Diterima</div>
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <a href="{{ route('barang.diterima', ['id' => $item->id]) }}"
                                                class="btn btn-success">Konfirmasi barang diterima</a>
                                        </div>
                                    @endif
                                </div>
                            @endif
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
                                    @foreach ($item->order_item as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->order_id }}</td>
                                            <td>{{ $orderItem->product->name }}</td>
                                            <td>{{ $orderItem->product_variant->name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->quantity }}</td>
                                            <td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @if ($item->status == 'Diterima')
                                            <tr>
                                                <td colspan="6">
                                                    <form action="{{ route('submit-review') }}" method="post">
                                                        @csrf
                                                        <input type="text" name="order_id" value="{{ $item->id }}"
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
                            @if ($item->status == 'Belum Dibayar')
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Nomor Rekening</h5>
                                        <p class="card-text">Silahkan transfer ke nomor rekening berikut:</p>
                                        @foreach ($bank as $bankDetail)
                                            <hr>
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="mr-2">{{ $bankDetail->bank_name }}</strong>
                                                <span
                                                    id="rekening-{{ $loop->index }}">{{ $bankDetail->account_number }}</span>
                                                <button onclick="copyToClipboard('rekening-{{ $loop->index }}')"
                                                    class="btn btn-sm btn-outline-primary ml-2">
                                                    <i class="fas fa-copy"></i> Salin
                                                </button>
                                            </div>
                                            <p class="card-text">
                                                Atas Nama <strong>{{ $bankDetail->account_name }}</strong>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                                <form action="{{ route('upload-bukti') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $item->id }}">
                                    <div class="form-group">
                                        <label for="bukti_transfer">Upload Bukti Transfer</label>
                                        <input type="file" class="form-control" id="bukti_transfer"
                                            name="bukti_transfer">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
