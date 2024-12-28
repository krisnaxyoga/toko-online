<!DOCTYPE html>
<html>

<head>
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .total {
            font-weight: bold;
        }

        .bank-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Invoice Order #{{ $order->id }}</h2>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                @foreach ($order->order_item as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <?php $total += $item->subtotal; ?>
                @endforeach
                <tr>
                    <td colspan="3">Ongkir ({{ $order->shipping_courier }})</td>
                    <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td colspan="3">Total</td>
                    <td>Rp {{ number_format($total + $order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="bank-info">
            <h4>Informasi Pembayaran:</h4>
            <p>
                Bank BCA<br>
                No. Rekening: 857-1234-5678<br>
                Atas Nama: John Doe
            </p>
        </div>
    </div>
</body>

</html>
