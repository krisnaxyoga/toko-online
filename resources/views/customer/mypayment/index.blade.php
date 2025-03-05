@extends('layouts.cust')

@section('contents')
    <section>
        <div class="container">
            <h2>Data My Payment</h2>
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
                            <h5 class="card-title">Data Payment</h5>

                            <!-- Table with stripped rows -->
                            <div class="table-responsive">

                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>
                                                Number
                                            </th>
                                            <th>
                                                ID Order
                                            </th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                </td>
                                                <td>
                                                    <a href="{{ route('mypayment.detail', $item->order_id) }}">
                                                        {{ $item->order->invoice_number }}</a>
                                                </td>
                                                <td>{{ $item->created_at }}
                                                </td>
                                                <td>{{ $item->status }}</td>

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
    </section>
@endsection
