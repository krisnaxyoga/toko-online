@extends('layouts.front')
@section('contents')
    <!-- breadcrumb remains the same -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Shopping Cart -->
    <form class="bg0 p-t-75 p-b-85" action="{{ route('front.checkout') }}" method="POST">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Product</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Quantity</th>
                                    <th class="column-5">Total</th>
                                </tr>

                                @php
                                    $subtotal = 0;
                                @endphp

                                @if (empty($cart))
                                    <tr class="table_row">
                                        <td colspan="5" class="text-center p-4">
                                            Your cart is empty
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($cart as $item)
                                        @php
                                            $price = $item->product_variant_id
                                                ? $item->product_variant->price_adjustment
                                                : $item->product->price;
                                            $subtotal += $price * $item->quantity;
                                        @endphp
                                        <tr class="table_row">
                                            <td class="column-1">
                                                <div class="how-itemcart1">
                                                    <img src="{{ url($item->product->images->where('is_primary', 1)->first()->image_url) }}"
                                                        alt="IMG">
                                                </div>
                                            </td>
                                            <td class="column-2">
                                                {{ $item->product->name }}
                                                @if ($item->product_variant_id)
                                                    <br>
                                                    <small>{{ $item->product_variant->name }}</small>
                                                @endif
                                            </td>
                                            @if ($item->product_variant_id)
                                                <td class="column-3">Rp
                                                    {{ number_format($item->product_variant->price_adjustment, 0, ',', '.') }}
                                                </td>
                                            @else
                                                <td class="column-3">Rp
                                                    {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                            @endif
                                            <td class="column-4">
                                                <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                    <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"
                                                        onclick="updateQuantity('{{ $item->id }}', 'decrease')">
                                                        <i class="fs-16 zmdi zmdi-minus"></i>
                                                    </div>

                                                    <input id="quantity-{{ $item['id'] }}"
                                                        class="mtext-104 cl3 txt-center num-product" type="number"
                                                        name="quantity" value="{{ $item->quantity }}" readonly>

                                                    <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"
                                                        onclick="updateQuantity('{{ $item->id }}', 'increase')">
                                                        <i class="fs-16 zmdi zmdi-plus"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            @if ($item->product_variant_id)
                                                <td class="column-5" id="total-{{ $item->id }}">
                                                    Rp
                                                    {{ number_format($item->product_variant->price_adjustment * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            @else
                                                <td class="column-5" id="total-{{ $item->id }}">
                                                    Rp
                                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Cart Totals
                        </h4>

                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Subtotal:
                                </span>
                            </div>

                            <div class="size-209">
                                <span class="mtext-110 cl2">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Shipping section remains the same -->
                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm">
                                <span class="stext-110 cl2">
                                    Shipping:
                                </span>
                            </div>

                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <p class="stext-111 cl6 p-t-2">
                                    There are no shipping methods available. Please double check your address, or contact us
                                    if you need any help.
                                </p>

                                <div class="p-t-15">
                                    <span class="stext-112 cl8">
                                        Calculate Shipping
                                    </span>
                                    <br>
                                    <strong>Destination</strong>
                                    @forelse ($customer->user_address as $address)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="destination_city"
                                                id="destination_city_{{ $address->id }}" data-id="{{ $address->id }}"
                                                value="{{ $address->city->id }}" required>
                                            <label class="form-check-label" for="destination_city_{{ $address->id }}">
                                                {{ $address->address }},
                                                {{ $address->city->name }}, {{ $address->province->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <p>No Address</p>
                                    @endforelse

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="courier" class="form-label">Courier</label>
                                            <select name="courier" id="courier" class="form-select" required>
                                                <option value="">Choose Courier</option>
                                                <option value="jne">JNE</option>
                                                <option value="pos">POS</option>
                                                <option value="tiki">TIKI</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            {{-- <label for="weight" class="form-label">Weight (Gram)</label> --}}
                                            <input hidden type="number" name="weight" id="weight" class="form-control"
                                                value="{{ $weight }}" readonly>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="notes" class="form-label">Note</label>
                                            <textarea name="notes" id="notes" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="flex-w">
                                        <div
                                            class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                                            Check shipping
                                        </div>
                                    </div> --}}
                                    <input type="hidden" id="shipping_cost" value="0">
                                </div>
                            </div>
                        </div>
                        <!-- Display Subtotal -->
                        {{-- <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Subtotal:
                                </span>
                            </div>
                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div> --}}
                        <input type="text" name="total_price" value="{{ $subtotal }}" hidden>
                        <!-- Display Shipping Cost -->
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Courier :
                                </span>
                            </div>
                            <div class="size-209 p-t-1">
                                <select id="shipping_options" class="form-select" name="shipping_cost" required>
                                    <option value="" disabled selected>Select a price shipping</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Shipping:
                                </span>
                            </div>
                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2" id="shipping_display">
                                    Rp 0
                                </span>
                            </div>
                        </div>

                        <!-- Display Total -->
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Total:
                                </span>
                            </div>
                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2" id="total_display">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                                <input type="text" name="grand_total" id="total" value="" hidden>
                                <input type="text" name="shipping_address_id" value="" id="shipping_address_id"
                                    hidden>
                            </div>
                        </div>

                        <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#courier').on('change', function(e) {
                e.preventDefault();
                let origin = {{ $store->city_id }}; //$('#origin_city').val();
                let destination = $('input[name="destination_city"]:checked').val();
                let address = $('input[name="destination_city"]:checked').data('id');
                let courier = $('#courier').val();
                let weight = $('#weight').val();

                // Menonaktifkan tombol selama proses
                $('#shipping_options').html('Loading...');
                $('#shipping_options').attr('disabled', true);

                $.ajax({
                    url: "{{ route('check-ongkir') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        origin: origin,
                        destination: destination,
                        courier: courier,
                        weight: weight
                    },
                    success: function(response) {
                        $('#shipping_options').html('Update Totals');
                        $('#shipping_options').attr('disabled', false);

                        // Kosongkan dan isi dropdown ongkir
                        $('#shipping_options').empty();
                        $('#shipping_options').append(
                            '<option value="" disabled selected>Select a shipping method</option>'
                        );

                        // Periksa jika response ongkir tersedia
                        if (response.length > 0) {
                            let subtotal =
                                {{ $subtotal }}; // Subtotal yang sudah ada dalam variabel PHP

                            $.each(response, function(index, option) {
                                let cost = option.cost[0].value; // Ongkir
                                let description = option
                                    .description; // Deskripsi Ongkir
                                let service = option.service; // Kode layanan
                                let etd = option.cost[0]
                                    .etd; // Estimasi waktu pengiriman

                                // Tambahkan pilihan ongkir ke dropdown
                                $('#shipping_options').append(`
                        <option value="${cost}" data-service="${service}" data-description="${description}" data-etd="${etd}">
                            ${description} (${etd}): Rp ${cost.toLocaleString()}
                        </option>
                    `);
                            });

                            // Event ketika pilihan ongkir berubah
                            $('#shipping_options').on('change', function() {
                                let shippingCost = parseInt($(this)
                                    .val()); // Ongkir yang dipilih
                                let total = subtotal +
                                    shippingCost; // Tambahkan ongkir ke subtotal

                                // Memperbarui nilai ongkir yang dipilih
                                $('#shipping_display').text('Rp ' + shippingCost
                                    .toLocaleString());

                                // Memperbarui total yang ditampilkan
                                $('#total_display').text('Rp ' + total
                                    .toLocaleString());

                                // Memperbarui nilai id total
                                $('#total').val(total);
                                $('#shipping_address_id').val(address);

                            });
                        } else {
                            // Jika tidak ada ongkir
                            alert('No shipping methods available.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Failed to fetch shipping cost. Please try again.');
                    }
                });
            });
        });
    </script>
    <script>
        function updateQuantity(id, action) {
            const input = document.getElementById(`quantity-${id}`);
            let quantity = parseInt(input.value);

            // Update quantity based on action
            if (action == 'increase') {
                quantity += 1;
            } else if (action == 'decrease') {
                quantity -= 1;
            }


            // Send update to server
            fetch(`{{ route('cart.update', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: `quantity=${quantity}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    location.reload(); // Refresh page to update all totals
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                });
        }
    </script>
@endsection
