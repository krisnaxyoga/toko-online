@extends('layouts.admin')

@section('contents')
    <!-- Di bagian head atau sebelum closing body -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <section>
        <div class="container">
            <h2>Report</h2>
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
                                            <th>total item</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                            <tr data-created-at="{{ $item->created_at }}">
                                                <td>
                                                    {{ $loop->index + 1 }}
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
                                                <td>{{ $item->user_address->address }}</td>
                                                <td>{{ $item->notes }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l d F Y') }}
                                                </td>
                                                <td>{{ $item->order_item->sum('quantity') }}</td>
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
                                        {{-- <th>Product Variant ID</th> --}}
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
                                            {{-- <td>{{ $orderItem->product_variant_id ?? 'N/A' }}</td> --}}
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
        @endforeach
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add filter controls to the page
            const filterControls = `
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Filter Type</label>
                        <select class="form-select" id="filterType">
                            <option value="all">All Time</option>
                            <option value="daily">Daily</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-none" id="dateContainer">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="singleDate">
                    </div>
                    <div class="col-md-2 d-none" id="monthContainer">
                        <label class="form-label">Month</label>
                        <input type="month" class="form-control" id="monthPicker">
                    </div>
                    <div class="col-md-2 d-none" id="yearContainer">
                        <label class="form-label">Year</label>
                        <select class="form-select" id="yearPicker">
                            ${generateYearOptions()}
                        </select>
                    </div>
                    <div class="col-md-2 d-none" id="weekContainer">
                        <label class="form-label">Week</label>
                        <input type="week" class="form-control" id="weekPicker">
                    </div>
                    <div class="col-md-4 d-none" id="customRangeContainer">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate">
                            </div>
                            <div class="col-6">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="applyFilter()">Apply Filter</button>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button class="btn btn-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

            // Insert filter controls before the table
            document.querySelector('.table-responsive').insertAdjacentHTML('beforebegin', filterControls);

            // Show/hide date inputs based on filter type
            document.getElementById('filterType').addEventListener('change', function() {
                const containers = ['dateContainer', 'monthContainer', 'yearContainer',
                    'weekContainer', 'customRangeContainer'
                ];
                containers.forEach(container => {
                    document.getElementById(container).classList.add('d-none');
                });

                switch (this.value) {
                    case 'daily':
                        document.getElementById('dateContainer').classList.remove('d-none');
                        break;
                    case 'weekly':
                        document.getElementById('weekContainer').classList.remove('d-none');
                        break;
                    case 'monthly':
                        document.getElementById('monthContainer').classList.remove('d-none');
                        break;
                    case 'yearly':
                        document.getElementById('yearContainer').classList.remove('d-none');
                        break;
                    case 'custom':
                        document.getElementById('customRangeContainer').classList.remove('d-none');
                        break;
                }
            });

            // Add created_at as data attribute to each row
            addCreatedAtDataAttribute();
        });

        // function addCreatedAtDataAttribute() {
        //     const rows = document.querySelectorAll('.datatable tbody tr');
        //     rows.forEach(row => {
        //         // Get the created_at value from blade and add it as a data attribute
        //         const createdAt = row.getAttribute('data-created-at');
        //         console.log(createdAt);
        //         if (createdAt) {
        //             row.dataset.date = new Date(createdAt).toISOString().split('T')[0];
        //         }
        //     });
        // }

        function generateYearOptions() {
            const currentYear = new Date().getFullYear();
            let options = '';
            for (let year = currentYear; year >= currentYear - 5; year--) {
                options += `<option value="${year}">${year}</option>`;
            }
            return options;
        }

        function applyFilter() {
            const filterType = document.getElementById('filterType').value;
            let startDate, endDate;

            switch (filterType) {
                case 'daily':
                    const selectedDate = document.getElementById('singleDate').value;
                    startDate = selectedDate;
                    endDate = selectedDate;
                    break;
                case 'weekly':
                    const weekData = document.getElementById('weekPicker').value.split('-W');
                    const weekDates = getWeekDates(weekData[0], weekData[1]);
                    startDate = weekDates.start;
                    endDate = weekDates.end;
                    break;
                case 'monthly':
                    const monthData = document.getElementById('monthPicker').value.split('-');
                    startDate = `${monthData[0]}-${monthData[1]}-01`;
                    endDate = getLastDayOfMonth(monthData[0], monthData[1]);
                    break;
                case 'yearly':
                    const year = document.getElementById('yearPicker').value;
                    startDate = `${year}-01-01`;
                    endDate = `${year}-12-31`;
                    break;
                case 'custom':
                    startDate = document.getElementById('startDate').value;
                    endDate = document.getElementById('endDate').value;
                    break;
                case 'all':
                    showAllRows();
                    return;
                default:
                    return;
            }

            filterTableRows(startDate, endDate);
        }

        // function filterTableRows(startDate, endDate) {
        //     const rows = document.querySelectorAll('.datatable tbody tr');
        //     const start = new Date(startDate);
        //     const end = new Date(endDate);
        //     end.setHours(23, 59, 59, 999); // Include the entire end date

        //     rows.forEach(row => {
        //         const rowDate = new Date(row.dataset.date);
        //         if (rowDate >= start && rowDate <= end) {
        //             row.style.display = '';
        //         } else {
        //             row.style.display = 'none';
        //         }
        //     });
        // }

        function filterTableRows(startDate, endDate) {
            const rows = document.querySelectorAll('.datatable tbody tr');

            // Set start date to beginning of day (00:00:00.000)
            const start = new Date(startDate);
            start.setHours(0, 0, 0, 0);

            // Set end date to end of day (23:59:59.999)
            const end = new Date(endDate);
            end.setHours(23, 59, 59, 999);

            rows.forEach(row => {
                const rowDate = new Date(row.dataset.date);
                // Set row date to start of day for consistent comparison
                rowDate.setHours(0, 0, 0, 0);

                if (rowDate >= start && rowDate <= end) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Also update how we store the date attribute
        function addCreatedAtDataAttribute() {
            const rows = document.querySelectorAll('.datatable tbody tr');
            rows.forEach(row => {
                const createdAt = row.getAttribute('data-created-at');
                if (createdAt) {
                    // Store full ISO date string to preserve timezone information
                    row.dataset.date = new Date(createdAt).toISOString();
                }
            });
        }

        function showAllRows() {
            const rows = document.querySelectorAll('.datatable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        // ... (fungsi export dan helper lainnya tetap sama)

        function getFilterTitle() {
            const filterType = document.getElementById('filterType').value;
            let title = 'Orders Report';

            switch (filterType) {
                case 'daily':
                    const date = new Date(document.getElementById('singleDate').value);
                    title += ` - ${date.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' })}`;
                    break;
                case 'monthly':
                    const monthValue = document.getElementById('monthPicker').value;
                    const monthDate = new Date(monthValue + '-01');
                    title += ` - ${monthDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;
                    break;
                case 'yearly':
                    const year = document.getElementById('yearPicker').value;
                    title += ` - ${year}`;
                    break;
                case 'weekly':
                    const weekData = document.getElementById('weekPicker').value.split('-W');
                    const weekDates = getWeekDates(weekData[0], weekData[1]);
                    title += ` - Week ${weekData[1]}, ${weekData[0]}`;
                    break;
                case 'custom':
                    const startDate = new Date(document.getElementById('startDate').value);
                    const endDate = new Date(document.getElementById('endDate').value);
                    title += ` (${startDate.toLocaleDateString('en-US')} - ${endDate.toLocaleDateString('en-US')})`;
                    break;
            }
            return title;
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            const visibleRows = Array.from(document.querySelectorAll('.datatable tbody tr'))
                .filter(row => row.style.display !== 'none');

            visibleRows.forEach(row => {
                const totalCell = row.querySelector('td:nth-child(7)'); // Column containing total
                const totalText = totalCell.textContent.replace('Rp ', '').replace(/\./g, '');
                grandTotal += parseInt(totalText);
            });
            return grandTotal;
        }

        function exportToExcel() {
            // Get visible rows
            const visibleRows = Array.from(document.querySelectorAll('.datatable tbody tr'))
                .filter(row => row.style.display !== 'none');

            // Create new array with only required columns
            const exportData = visibleRows.map(row => ({
                'Customer': row.querySelector('td:nth-child(3)').textContent,
                'Date': new Date(row.getAttribute('data-created-at')).toLocaleDateString('en-US'),
                'Total Items': row.querySelector('td:nth-child(12)')
                    .textContent, // You might need to adjust this based on your actual data structure
                'Total Price': row.querySelector('td:nth-child(7)').textContent
            }));

            // Add grand total row
            const grandTotal = calculateGrandTotal();
            exportData.push({
                'Customer': '',
                'Date': '',
                'Total Items': 'Grand Total:',
                'Total Price': `Rp ${grandTotal.toLocaleString('id-ID')}`
            });

            // Convert to worksheet
            const ws = XLSX.utils.json_to_sheet(exportData);
            const wb = XLSX.utils.book_new();

            // Add title to worksheet
            XLSX.utils.sheet_add_aoa(ws, [
                [getFilterTitle()]
            ], {
                origin: 'A1'
            });

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Orders');

            // Generate filename and save
            const filterType = document.getElementById('filterType').value;
            const filename = `orders_${filterType}_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, filename);
        }

        function exportToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Add title
            const title = getFilterTitle();
            doc.setFontSize(16);
            doc.text(title, 14, 15);

            // Prepare the data for selected columns only
            const visibleRows = Array.from(document.querySelectorAll('.datatable tbody tr'))
                .filter(row => row.style.display !== 'none');

            const exportData = visibleRows.map(row => [
                row.querySelector('td:nth-child(3)').textContent, // Customer
                new Date(row.getAttribute('data-created-at')).toLocaleDateString('en-US'), // Date
                row.querySelector('td:nth-child(12)').textContent, // Total Items (adjust as needed)
                row.querySelector('td:nth-child(7)').textContent // Total Price
            ]);

            // Calculate grand total
            const grandTotal = calculateGrandTotal();

            // Add grand total row
            exportData.push(['', '', 'Grand Total:', `Rp ${grandTotal.toLocaleString('id-ID')}`]);

            // Generate table
            doc.autoTable({
                head: [
                    ['Customer', 'Date', 'Total Items', 'Total Price']
                ],
                body: exportData,
                startY: 25,
                theme: 'grid',
                headStyles: {
                    fillColor: [41, 128, 185],
                    textColor: [255, 255, 255]
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                }
            });

            // Generate filename and save
            const filterType = document.getElementById('filterType').value;
            const filename = `orders_${filterType}_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(filename);
        }

        function getWeekDates(year, week) {
            const firstDayOfYear = new Date(year, 0, 1);
            const daysToFirstMonday = (8 - firstDayOfYear.getDay()) % 7;
            const firstMonday = new Date(year, 0, 1 + daysToFirstMonday);

            const startDate = new Date(firstMonday);
            startDate.setDate(startDate.getDate() + (week - 1) * 7);

            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 6);

            return {
                start: startDate.toISOString().split('T')[0],
                end: endDate.toISOString().split('T')[0]
            };
        }

        function getLastDayOfMonth(year, month) {
            return new Date(year, month, 0).toISOString().split('T')[0];
        }
    </script>
    <style>
        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .btn-group {
            gap: 0.5rem;
        }

        .datatable th {
            white-space: nowrap;
        }

        #filterControls {
            margin-bottom: 1rem;
        }

        .d-none {
            display: none !important;
        }
    </style>
@endsection
