@extends('layouts.admin')

@section('contents')
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

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
                        method="POST" enctype="multipart/form-data" id="productForm">
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
                            <label for="inputImages" class="form-label">Images primary</label>
                            <input type="file" class="form-control" id="inputImages" name="images_primary"
                                accept="image/*" onchange="previewImage(event)">
                            @if ($model->exists && $image_primary != null)
                                <img src="{{ url($image_primary->image_url) }}" width="200px" class="mt-2"
                                    id="output" />
                            @else
                                <img id="output" width="200px" class="mt-2" />
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputStatus" class="form-label">Status</label>
                            <select class="form-select" id="inputStatus" name="status">
                                <option value="available"
                                    {{ old('status', $model->status ?? '') == 'available' ? 'selected' : '' }}>
                                    Available
                                </option>
                                <option value="no_available"
                                    {{ old('status', $model->status ?? '') == 'no_available' ? 'selected' : '' }}>
                                    No Available
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Upload gallery</label>
                                <input type="file" class="form-control" name="images[]" multiple
                                    data-allow-reorder="true" accept="image/*">
                            </div>
                            @if ($model->exists && $model->images)
                                <div class="row">
                                    @foreach ($model->images as $image)
                                        <div class="col-md-3">
                                            <img src="{{ url($image->image_url) }}" width="100%" />
                                        </div>
                                    @endforeach
                                </div>
                            @endif
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


    <script>
        // Store selected files globally
        let selectedFiles = [];

        // Get elements
        const fileInput = document.querySelector('input[name="images[]"]');
        const previewContainer = document.createElement('div');
        previewContainer.className = 'preview-container mt-3';
        previewContainer.style.display = 'flex';
        previewContainer.style.flexWrap = 'wrap';
        previewContainer.style.gap = '10px';

        // Insert preview container after file input
        fileInput.parentNode.insertBefore(previewContainer, fileInput.nextSibling);

        // Handle file selection
        fileInput.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);

            // Add new files to selectedFiles array
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.push(file);
                    createPreviewImage(file);
                }
            });

            // Update the file input's FileList
            updateFileInput();
        });

        // Create preview image
        function createPreviewImage(file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewWrapper = document.createElement('div');
                previewWrapper.className = 'preview-wrapper';
                previewWrapper.style.position = 'relative';
                previewWrapper.style.width = '150px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '150px';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';

                const deleteButton = document.createElement('button');
                deleteButton.innerHTML = '×';
                deleteButton.className = 'delete-button';
                deleteButton.style.position = 'absolute';
                deleteButton.style.top = '5px';
                deleteButton.style.right = '5px';
                deleteButton.style.backgroundColor = 'red';
                deleteButton.style.color = 'white';
                deleteButton.style.border = 'none';
                deleteButton.style.borderRadius = '50%';
                deleteButton.style.width = '25px';
                deleteButton.style.height = '25px';
                deleteButton.style.cursor = 'pointer';
                deleteButton.style.display = 'flex';
                deleteButton.style.alignItems = 'center';
                deleteButton.style.justifyContent = 'center';

                deleteButton.onclick = function() {
                    // Remove file from selectedFiles array
                    const index = selectedFiles.indexOf(file);
                    if (index > -1) {
                        selectedFiles.splice(index, 1);
                    }

                    // Remove preview
                    previewWrapper.remove();

                    // Update the file input
                    updateFileInput();
                };

                previewWrapper.appendChild(img);
                previewWrapper.appendChild(deleteButton);
                previewContainer.appendChild(previewWrapper);
            };

            reader.readAsDataURL(file);
        }

        // Update file input with current selectedFiles
        function updateFileInput() {
            // Create a new DataTransfer object
            const dataTransfer = new DataTransfer();

            // Add all selected files to the DataTransfer object
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            // Set the file input's files to the DataTransfer files
            fileInput.files = dataTransfer.files;
        }

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // The selected files will automatically be included in the form data
            // because we're keeping the file input updated
            console.log('Submitting files:', selectedFiles.map(f => f.name));
        });
    </script>
@endsection
