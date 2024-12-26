@extends('layouts.front')
@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Checkout Success</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($order_items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <?php $total += $item->subtotal; ?>
                            @endforeach
                            <tr>
                                <th colspan="3">Ongkir</th>
                                <th>{{ $order->shipping_courier }}</th>
                                <th>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-right">Rp {{ number_format($total + $order->shipping_cost, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tbody>
                    </table>


                </div>
                <div class="col-lg-6">
                    <form action="{{ route('upload-bukti') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="form-group">
                            <label for="bukti_transfer">Upload Bukti Transfer</label>
                            <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
