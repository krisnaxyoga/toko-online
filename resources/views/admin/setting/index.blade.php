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
                                            <select name="origin_province" id="origin_province" class="form-select">

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
                                            <select name="origin_city" id="origin_city" class="form-select">

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
