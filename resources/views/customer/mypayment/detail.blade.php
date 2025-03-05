@extends('layouts.cust')

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
                                    <p>: {{ $data->payment->created_at }}
                                    </p>
                                    <p>: {{ $data->payment->status }}</p>
                                    <p>: {{ $data->grand_total }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if ($data->payment->status == 'Pembayaran Gagal')
                        <div class="card">
                            <div class="card-body m-3">
                                <form action="{{ route('upload-bukti-gagal') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $data->id }}">
                                    <div class="form-group">
                                        <label for="bukti_transfer">Upload Bukti Transfer lagi dikarenakan pembayaran
                                            gagal</label>
                                        <input type="file" class="form-control mb-2" id="bukti_transfer"
                                            name="bukti_transfer" accept=".jpg, .jpeg, .png, .JPG"
                                            onchange="validateFile(this)">
                                        <script>
                                            function validateFile(input) {
                                                const file = input.files[0];
                                                if (file) {
                                                    const validExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                                                    if (!validExtensions.includes(file.type)) {
                                                        alert('Hanya gambar dengan format jpg, jpeg, atau png yang boleh diupload.');
                                                        input.value = '';
                                                    }
                                                }
                                            }
                                        </script>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>

                        </div>
                    @else
                        <div class="card">
                            <div class="card-head m-4">
                                <h3>Payment Proof</h3>
                            </div>
                            <div class="card-body">
                                <img src="{{ url($data->payment->image) }}" alt="" style="width: 100%">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
