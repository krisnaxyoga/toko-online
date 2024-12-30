@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data bank</h2>
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
                            <h5 class="card-title">Data bank</h5>
                            <a href="{{ route('bank-account.create') }}" class="btn btn-success">add</a>
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
                                            <th>Account Number</th>
                                            <th>Account Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bank_accounts as $item)
                                            <tr>
                                                <td> <a href="{{ route('bank-account.edit', ['bank_account' => $item->id]) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            class="bx bxs-edit"></i></a>

                                                    <form action="{{ route('bank-account.destroy', $item->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                            class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                            onclick="return confirm('Yakin ingin menghapus data?')"><i
                                                                class="bx bxs-trash"></i></button>
                                                    </form>
                                                </td>
                                                <td>{{ $item->bank_name }}</td>
                                                <td>{{ $item->account_number }}</td>
                                                <td>{{ $item->account_name }}</td>
                                                <td>{{ $item->status }}</td>

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
