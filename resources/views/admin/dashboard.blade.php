@extends('layouts.admin')

@section('contents')
    <div class="container">
        <h2>Selamat Datang Superadmin</h2>

        <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">


                    <div class="card-body">

                        <h5 class="card-title">Sales</h5>

                        <div class="d-flex align-items-center">

                            <div class="ps-3">
                                <h6>{{ $penjualan }}</h6>


                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">



                    <div class="card-body">
                        <h5 class="card-title">Revenue</h5>

                        <div class="d-flex align-items-center">

                            <div class="ps-3">
                                <h6>Rp {{ number_format($revenue, 0, ',', '.') }}</h6>


                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

                <div class="card info-card customers-card">



                    <div class="card-body">
                        <h5 class="card-title">Customers</h5>

                        <div class="d-flex align-items-center">

                            <div class="ps-3">
                                <h6>{{ $customer }}</h6>

                            </div>
                        </div>

                    </div>
                </div>

            </div><!-- End Customers Card -->

        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card m-2">


                            <div class="card-body">
                                <h5 class="card-title">Reports </h5>

                                <!-- Line Chart -->
                                <div id="reportsChart"></div>
                                <?php
                                $data = [];

                                foreach ($orders as $order) {
                                    $date = (new DateTime($order->created_at))->format('Y-m-d');
                                    if (!isset($data[$date])) {
                                        $data[$date] = [
                                            'sales' => 0,
                                            'customers' => 0,
                                        ];
                                    }
                                    $data[$date]['sales'] += $order->grand_total;
                                    $data[$date]['customers'] += 1;
                                }

                                $salesData = [];
                                $customersData = [];
                                $labels = array_keys($data);

                                foreach ($labels as $date) {
                                    $salesData[] = $data[$date]['sales'];
                                    $customersData[] = $data[$date]['customers'];
                                }

                                // Now $salesData, $customersData, and $labels can be used to render the chart

                                ?>

                                <!-- End Line Chart -->

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">

                            <div class="card-body pb-0">
                                <h5 class="card-title">Revenue Diagram</span></h5>

                                <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        echarts.init(document.querySelector("#trafficChart")).setOption({
                                            tooltip: {
                                                trigger: 'item'
                                            },
                                            legend: {
                                                top: '5%',
                                                left: 'center'
                                            },
                                            series: [{
                                                name: 'Access From',
                                                type: 'pie',
                                                radius: ['40%', '70%'],
                                                avoidLabelOverlap: false,
                                                label: {
                                                    show: false,
                                                    position: 'center'
                                                },
                                                emphasis: {
                                                    label: {
                                                        show: true,
                                                        fontSize: '18',
                                                        fontWeight: 'bold'
                                                    }
                                                },
                                                labelLine: {
                                                    show: false
                                                },
                                                data: [
                                                    @foreach ($data as $date => $item)
                                                        {
                                                            value: {{ $item['sales'] }},
                                                            name: '{{ $date }}'
                                                        },
                                                    @endforeach
                                                ]
                                            }]
                                        });
                                    });
                                </script>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Reports -->

            </div>
            <div class="col-lg-12">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Data Orders</h5>
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
                                                    <th>shipping courier</th>
                                                    <th>shipping address</th>
                                                    <th>notes</th>
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
                                                                <i class="bi bi-eye"></i>
                                                            </button> --}}
                                                        </td>
                                                        <td>{{ $item->invoice_number }}</td>
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>{{ $item->status }}</td>
                                                        <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                                        <td>Rp {{ number_format($item->shipping_cost, 0, ',', '.') }}</td>
                                                        <td>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                                                        <td>{{ $item->shipping_courier }}</td>
                                                        <td>{{ $item->user_address ? $item->user_address->address : 'N/A' }}
                                                        </td>
                                                        <td>{{ $item->notes }}</td>
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

                @foreach ($orders as $item)
                    <div class="modal fade" id="orderItemsModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="orderItemsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="orderItemsModalLabel">Order Items for
                                        {{ $item->invoice_number }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="{{ route('resi.add.admin') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="hidden" name="order_id" value="{{ $item->id }}">
                                                    <label for="notes" class="form-label">Resi Pengiriman</label>
                                                    <input type="text" class="form-control" id="notes" name="resi"
                                                        value="{{ $item->shipping_tracking_number }}">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#reportsChart"), {
            series: [{
                name: 'Sales',
                data: <?= json_encode($salesData) ?>,
            }, {
                name: 'Customers',
                data: <?= json_encode($customersData) ?>,
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
            },
            markers: {
                size: 4
            },
            colors: ['#4154f1', '#2eca6a'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                type: 'datetime',
                categories: <?= json_encode($labels) ?>,
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy'
                },
            }
        }).render();
    });
</script>
