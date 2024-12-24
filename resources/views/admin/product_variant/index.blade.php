@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>{{ $product->name }}</h2>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ url($images_primary->image_url) }}" width="100%" />
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p>{{ $product->description }}</p>
                                    <p>Price : Rp {{ number_format($product->price, 0) }}</p>
                                    <p>Weight : {{ $product->weight }} Gr</p>
                                    <p>Stock : {{ $product->stock }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Products Variant : {{ $product->name }}</h5>
                            <a href="{{ route('productvariant.creatus', ['id' => $product->id]) }}"
                                class="btn btn-success">add</a>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">

                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productvariants as $item)
                                            <tr>
                                                <td> <a href="{{ route('productvariant.edit', ['productvariant' => $item->id]) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            class="bx bxs-edit"></i></a>

                                                    <form action="{{ route('productvariant.destroy', $item->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                            class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                            onclick="return confirm('Yakin ingin menghapus data?')"><i
                                                                class="bx bxs-trash"></i></button>
                                                    </form>
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->price_adjustment }}</td>
                                                <td>{{ $item->stock }}</td>
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
