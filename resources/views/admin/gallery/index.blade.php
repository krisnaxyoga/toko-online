@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data galleries</h2>
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
                            <h5 class="card-title">Data Categories</h5>
                            <a href="{{ route('gallery.create') }}" class="btn btn-success">add</a>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">

                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                image
                                            </th>
                                            <th>title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td> <a href="{{ route('gallery.edit', ['gallery' => $item->id]) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            class="bx bxs-edit"></i></a>
                                                    <a href="{{ route('gallery.show', ['gallery' => $item->id]) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            class="bx bxs-show"></i></a>
                                                    <form action="{{ route('gallery.destroy', $item->id) }}" method="post"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                            class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                            onclick="return confirm('Yakin ingin menghapus data?')"><i
                                                                class="bx bxs-trash"></i></button>
                                                    </form>
                                                </td>
                                                <td><img src="{{ url($item->image) }}" style="width: 200px;height:200px"
                                                        alt=""></td>
                                                <td>{{ $item->title }}</td>

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
