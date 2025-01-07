@extends('layouts.cust')

@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Dashboard Customer Selamat Datang</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card info-card revenue-card">



                        <div class="card-body">
                            <h5 class="card-title">Total Order</h5>

                            <div class="d-flex align-items-center">

                                <div class="ps-3">
                                    <h6>{{ $orders->count() }}</h6>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card info-card revenue-card">



                        <div class="card-body">
                            <h5 class="card-title">Total Transaction</h5>

                            <div class="d-flex align-items-center">

                                <div class="ps-3">
                                    <h6>Rp {{ number_format($orders->sum('grand_total'), 0, ',', '.') }}</h6>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
