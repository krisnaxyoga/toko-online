@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data Product variant</h2>
            <div class="card">
                <div class="card-body">
                    <!-- Multi Columns Form -->
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
                    <form class="row g-3 mt-2"
                        action="@if ($model->exists) {{ route('productvariant.update', ['productvariant' => $model->id]) }} @else {{ route('productvariant.store') }} @endif"
                        method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method($model->exists ? 'PUT' : 'POST')
                        <div class="col-md-6">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name"
                                value="{{ old('name', $model->name ?? '') }}">
                        </div>
                        <input type="text" name="product_id" id="" hidden value="{{ $id }}">
                        <div class="col-md-6">
                            <label for="inputPrice" class="form-label">Price adjustment</label>
                            <input type="number" class="form-control" id="inputPrice" name="price_adjustment"
                                value="{{ old('price_adjustment', $model->price_adjustment ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputStock" class="form-label">Stock</label>
                            <input type="text" class="form-control" id="inputStock" name="stock"
                                value="{{ old('stock', $model->stock ?? '') }}">
                        </div>

                        <div class="text-center">
                            <button type="submit"
                                class="btn btn-primary">{{ $model->exists ? 'Update' : 'Submit' }}</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
