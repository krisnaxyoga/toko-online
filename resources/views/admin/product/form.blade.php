@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data Product</h2>
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
                        action="@if ($model->exists) {{ route('product.update', ['product' => $model->id]) }} @else {{ route('product.store') }} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($model->exists ? 'PUT' : 'POST')
                        <div class="col-md-6">
                            <label for="inputCategory" class="form-label">Category</label>
                            <select class="form-select" id="inputCategory" name="category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $model->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name"
                                value="{{ old('name', $model->name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputSlug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="inputSlug" name="slug"
                                value="{{ old('slug', $model->slug ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="inputDescription" name="description">{{ old('description', $model->description ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="inputPrice" name="price"
                                value="{{ old('price', $model->price ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputWeight" class="form-label">Weight</label>
                            <input type="text" class="form-control" id="inputWeight" name="weight"
                                value="{{ old('weight', $model->weight ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputStock" class="form-label">Stock</label>
                            <input type="text" class="form-control" id="inputStock" name="stock"
                                value="{{ old('stock', $model->stock ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputStatus" class="form-label">Status</label>
                            <input type="text" class="form-control" id="inputStatus" name="status"
                                value="{{ old('status', $model->status ?? '') }}">
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
