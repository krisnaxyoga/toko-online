@extends('layouts.admin')

@section('contents')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        @if ($store_setting->logo_url)
                            <img src="{{ url($store_setting->logo_url) }}" alt="Profile" class="rounded-circle">
                        @endif
                        <h2>{{ $store_setting->store_name }}</h2>

                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile</button>
                            </li>


                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Store detail</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Store Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $store_setting->store_name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Province</div>
                                    @if ($store_setting->province)
                                        <div class="col-lg-9 col-md-8">{{ $store_setting->province->name }}</div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Province</div>
                                    @if ($store_setting->city)
                                        <div class="col-lg-9 col-md-8">{{ $store_setting->city->name }}</div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ $store_setting->address }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ $store_setting->phone }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $store_setting->email }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form action="{{ route('store-setting.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">logo
                                            store</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if ($store_setting->logo_url)
                                                <img src="{{ url($store_setting->logo_url) }}" alt="Profile">
                                            @endif
                                            <div class="pt-2">
                                                <input type="file" class="form-control" id="profileImage" name="logo">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Store Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="store_name" type="text" class="form-control" id="fullName"
                                                value="{{ $store_setting->store_name }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text" class="form-control" id="Address"
                                                value="{{ $store_setting->address }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" type="text" class="form-control" id="Phone"
                                                value="{{ $store_setting->phone }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="Email"
                                                value="{{ $store_setting->email }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="postal_code" class="col-md-4 col-lg-3 col-form-label">Postal
                                            Code</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="postal_code" type="postal_code" class="form-control"
                                                id="postal_code" value="{{ $store_setting->postal_code }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="province" class="col-md-4 col-lg-3 col-form-label">Province :
                                            @if ($store_setting->province_id)
                                                {{ $store_setting->province->name }}
                                            @endif
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="origin_province" id="origin_province"
                                                class="form-select form-control">

                                                <option>Choose Province</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="city" class="col-md-4 col-lg-3 col-form-label">City : @if ($store_setting->city_id)
                                                {{ $store_setting->city->name }}
                                            @endif
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="origin_city" id="origin_city" class="form-select form-control">

                                                <option>Choose City</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sliders</h5>
                        @if ($sliders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sliders as $slider)
                                            <tr>
                                                <td><img src="{{ url($slider->image) }}" class="img-fluid"
                                                        alt="{{ $slider->title }}" style="max-width: 150px;"></td>
                                                <td>{{ $slider->title }}</td>
                                                <td>{{ $slider->description }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSliderModal{{ $slider->id }}"
                                                            data-id="{{ $slider->id }}"
                                                            data-title="{{ $slider->title }}"
                                                            data-description="{{ $slider->description }}"
                                                            data-button-text="{{ $slider->button_text }}"
                                                            data-button-link="{{ $slider->button_link }}">Edit</button>
                                                        <form
                                                            action="{{ route('admin.setting.slider.destroy', $slider->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this slider?')">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No sliders available. Please add some sliders.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Slider</h5>

                        <!-- General Form Elements -->
                        <form method="POST" action="{{ route('admin.setting.slider.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="title" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" required></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="image" class="col-sm-2 col-form-label">Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="image" id="image" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="button_text" class="col-sm-2 col-form-label">Button Text</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="button_text" id="button_text"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="button_link" class="col-sm-2 col-form-label">Button Link</label>
                                <div class="col-sm-10">
                                    <input type="url" class="form-control" name="button_link" id="button_link"
                                        required>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Add Slider</button>
                            </div>
                        </form><!-- End Add Slider Form -->

                        @forelse($sliders as $key => $value)
                            <div class="modal fade" id="editSliderModal{{ $value->id }}" tabindex="-1"
                                aria-labelledby="editSliderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editSliderModalLabel">Edit Slider</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.setting.slider.update', $value->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" id="slider_id">
                                                <div class="row mb-3">
                                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="title"
                                                            value="{{ $value->title }}" id="title-edit" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="description"
                                                        class="col-sm-2 col-form-label">Description</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="description" id="description-edit" required>{{ $value->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="image" class="col-sm-2 col-form-label">Image</label>
                                                    <div class="col-sm-10">
                                                        <img src="{{ url($value->image) }}" class="img-fluid"
                                                            alt="{{ $value->title }}" style="max-width: 150px;">
                                                        <input type="file" class="form-control" name="image"
                                                            id="image-edit">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="button_text" class="col-sm-2 col-form-label">Button
                                                        Text</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="button_text"
                                                            id="button_text-edit" value="{{ $value->button_text }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="button_link" class="col-sm-2 col-form-label">Button
                                                        Link</label>
                                                    <div class="col-sm-10">
                                                        <input type="url" class="form-control" name="button_link"
                                                            value="{{ $value->button_link }}" id="button_link-edit"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Slider is empty</p>
                        @endforelse


                    </div>
                </div>

            </div>
        </div>

    </section>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#origin_province, #destination_province').select2({
                ajax: {
                    url: "{{ route('provinces') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        }
                    },
                }
            });

            $('#origin_city, #destination_city').select2();

            $('#origin_province').on('change', function() {
                $('#origin_city').empty();
                $('#origin_city').append('<option>Choose City</option>');
                $('#origin_city').select2('close');
                $('#origin_city').select2({
                    ajax: {
                        url: "{{ route('cities') }}",
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                keyword: params.term,
                                province_id: $('#origin_province').val()
                            }
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            }
                        },
                    }
                });
            });

            $('#destination_province').on('change', function() {
                $('#destination_city').empty();
                $('#destination_city').append('<option>Choose City</option>');
                $('#destination_city').select2('close');
                $('#destination_city').select2({
                    ajax: {
                        url: "{{ route('cities') }}",
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                keyword: params.term,
                                province_id: $('#destination_province').val()
                            }
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            }
                        },
                    }
                });
            });


        });
    </script>
@endsection
