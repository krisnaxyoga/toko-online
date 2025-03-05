@extends('layouts.front')
@section('contents')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <h1 class="text-center mb-4">Invoice</h1>

                </div>
            </div>

            <!-- Button Print & Email -->
            <div class="row mb-3">
                <div class="col-12">
                    <button onclick="printInvoice()" class="btn btn-secondary mr-2">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>
                    <button onclick="sendEmail()" class="btn btn-info">
                        <i class="fas fa-envelope"></i> Kirim ke Email
                    </button>
                </div>
            </div>

            <!-- Invoice Content -->


            <div class="row mb-5">
                <div class="col-lg-12" id="invoice-content">
                    <h2>Invoice Order #{{ $order->invoice_number }}</h2>
                    <!-- Tambahkan tulisan "Songket Jepun Bali" -->
                    <div class="row print-layout">
                        <div class="col-lg-6">
                            <div class="mb-0">
                                <h3>{{ $storeSetting->store_name }}</h3>
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <!-- Informasi Pembeli -->
                            <div class="mb-0">
                                <h4>Identitas Pembeli</h4>
                                <p class="mb-0"><strong>Nama:</strong> {{ $order->user->name }}</p>
                                <p class="mb-0"><strong>Alamat:</strong> {{ $order->user_address->address ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>HP:</strong> {{ $order->user->phone }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                <p><strong>Tgl
                                        Pesanan:</strong>{{ $order->created_at }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
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
                                <th colspan="2">Ongkir</th>
                                <th>{{ $order->shipping_courier }}</th>
                                <th>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="3">Total</th>
                                <th>Rp
                                    {{ number_format($total + $order->shipping_cost, 0, ',', '.') }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- CSS untuk print -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-content,
            #invoice-content * {
                visibility: visible;
            }

            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
            }

            .no-print {
                display: none !important;
            }

            /* Atur layout untuk nama toko dan informasi pembeli */
            .row.print-layout {
                display: flex;
                justify-content: space-between;
                width: 100%;
            }

            .print-layout .col-lg-6 {
                width: 48%;
                /* Sesuaikan lebar kolom */
            }

            .print-layout .col-lg-6:first-child {
                text-align: left;
                margin-bottom: 0px !important;
            }

            .print-layout .col-lg-6:last-child {
                text-align: left;
                margin-bottom: 0px !important;
            }
        }
    </style>

    <!-- JavaScript untuk fungsi copy, print dan email -->
    <script>
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).textContent;
            navigator.clipboard.writeText(text).then(() => {
                // Tampilkan feedback
                alert('Nomor rekening berhasil disalin!');
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }

        function printInvoice() {
            window.print();
        }

        function sendEmail() {
            const emailBtn = document.querySelector('button[onclick="sendEmail()"]');
            const originalBtnContent = emailBtn.innerHTML;

            // Disable button and show loading state
            emailBtn.disabled = true;
            emailBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Mengirim Email...
    `;

            const orderId = "{{ $order->id }}";
            const loading = document.createElement('div');
            loading.classList.add('d-flex', 'justify-content-center');
            loading.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
            document.body.appendChild(loading);

            fetch(`/send-invoice-email/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.body.removeChild(loading);
                    // Restore button state
                    emailBtn.disabled = false;
                    emailBtn.innerHTML = originalBtnContent;

                    if (data.success) {
                        alert('Invoice berhasil dikirim ke email Anda!');
                    } else {
                        console.error('Error:', data);
                        alert('Gagal mengirim email. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    document.body.removeChild(loading);
                    // Restore button state
                    emailBtn.disabled = false;
                    emailBtn.innerHTML = originalBtnContent;

                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
        }
    </script>

    <!-- Tambahkan Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
