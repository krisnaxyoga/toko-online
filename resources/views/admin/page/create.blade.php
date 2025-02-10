@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data About</h2>
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
                        action="@if ($model->exists) {{ route('pageabout.update', ['pageabout' => $model->id]) }} @else {{ route('pageabout.store') }} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($model->exists ? 'PUT' : 'POST')
                        <div class="col-md-6">
                            <label for="inputTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="inputTitle" name="title"
                                value="{{ old('title', $model->title ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputSlug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="inputSlug" name="slug"
                                value="{{ old('slug', $model->slug ?? '') }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="inputContent" class="form-label">Content</label>
                            <textarea class="form-control" id="inputContent" name="content" required>{{ old('content', $model->content ?? '') }}</textarea>
                        </div>
                        {{-- <div class="col-md-6">
                            <label for="inputMetaTitle" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="inputMetaTitle" name="meta_title"
                                value="{{ old('meta_title', $model->meta_title ?? '') }}">
                        </div> --}}
                        <div class="col-md-12">
                            <label for="inputMetaDescription" class="form-label">content 2</label>
                            <textarea class="form-control" id="inputContent" name="meta_description" required>{{ old('meta_description', $model->meta_description ?? '') }}</textarea>

                        </div>
                        {{-- <div class="col-md-6">
                            <label for="inputMetaKeywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="inputMetaKeywords" name="meta_keywords"
                                value="{{ old('meta_keywords', $model->meta_keywords ?? '') }}">
                        </div> --}}
                        <div class="col-md-6">
                            <label for="inputImage1" class="form-label">Image 1</label>
                            <input type="file" class="form-control" id="inputImage1" name="image1"
                                accept=".jpg, .jpeg, .png, .JPG" onchange="validateFile(this)" required>

                            @if ($model->image1)
                                <img src="{{ url($model->image1) }}" alt="{{ url($model->image1) }}"
                                    style="width: 200px;height:200px" class="mt-2">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputImage2" class="form-label">Image 2</label>
                            <input type="file" class="form-control" id="inputImage2" name="image2"
                                accept=".jpg, .jpeg, .png, .JPG" onchange="validateFile(this)" required>
                            @if ($model->image2)
                                <img src="{{ url($model->image2) }}" alt="{{ url($model->image2) }}"
                                    style="width: 200px;height:200px" class="mt-2">
                            @endif
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="btn btn-primary">{{ $model->exists ? 'Update' : 'Submit' }}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function validateFile(input) {
            const file = input.files[0];
            if (file) {
                const validExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validExtensions.includes(file.type)) {
                    alert('Hanya gambar dengan format jpg, jpeg, atau png yang boleh diupload.');
                    input.value = '';
                }
            }
        }
    </script>
@endsection
