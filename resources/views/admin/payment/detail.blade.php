@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Detail Payment {{ $data->invoice_number }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-head m-4">
                            <h3>Data Order</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Invoice Number</p>
                                    <p>Date</p>
                                    <p>Payment Status</p>
                                    <p>Payment Amount</p>
                                </div>
                                <div class="col-md-6">
                                    <p>: {{ $data->invoice_number }}</p>
                                    <p>: {{ \Carbon\Carbon::parse($data->payment->created_at)->translatedFormat('l d F Y') }}
                                    </p>
                                    <p>: {{ $data->status }}</p>
                                    <p>: {{ $data->grand_total }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-head m-4">
                            <h3>Payment Proof</h3>
                        </div>
                        <div class="card-body">
                            <img src="{{ url($data->payment->image) }}" alt="" style="width: 100%">

                            <div class="my-4">
                                <form action="{{ route('admin.payment.status', ['id' => $data->id]) }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-8">
                                            <select name="status" id="" class="form-control">
                                                <option value="">-pilih-</option>
                                                <option value="Pembayaran Diterima"
                                                    {{ $data->status == 'Pembayaran Diterima' ? 'selected' : '' }}>
                                                    Pembayaran Berhasil</option>
                                                <option value="Dibatalkan"
                                                    {{ $data->status == 'Dibatalkan' ? 'selected' : '' }}>Pembayaran Gagal
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary">Ok</button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body my-4">
                            <a href="{{ route('detailpesananorder', ['id' => $data->id]) }}" class="btn btn-primary">Proses
                                pesanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
