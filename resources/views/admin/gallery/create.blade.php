@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data Gallery</h2>
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
                        action="@if ($model->exists) {{ route('gallery.update', ['gallery' => $model->id]) }} @else {{ route('gallery.store') }} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($model->exists ? 'PUT' : 'POST')
                        <div class="col-md-6">
                            <label for="inputName5" class="form-label">title</label>
                            <input type="text" class="form-control" id="inputName5" name="title"
                                value="{{ old('title', $model->title ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            @if ($model->image)
                                <img src="{{ $model->image }}" alt="" style="width: 200px;height:200px"
                                    class="mb-3">
                            @endif
                            <label for="inputEmail5" class="form-label">image</label>
                            <input type="file" class="form-control" name="image">
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
