@extends('layouts.admin')

@section('contents')
    <section>
        <div class="container">
            <h2>Data Bank Account</h2>
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
                        action="@if ($model->exists) {{ route('bank-account.update', ['bank_account' => $model->id]) }} @else {{ route('bank-account.store') }} @endif"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($model->exists ? 'PUT' : 'POST')
                        <div class="col-md-6">
                            <label for="inputAccountNumber" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="inputAccountNumber" name="account_number"
                                value="{{ old('account_number', $model->account_number ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputAccountName" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="inputAccountName" name="account_name"
                                value="{{ old('account_name', $model->account_name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputBankName" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="inputBankName" name="bank_name"
                                value="{{ old('bank_name', $model->bank_name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputStatus" class="form-label">Status</label>
                            <select class="form-select" id="inputStatus" name="status" required>
                                <option value="active"
                                    {{ old('status', $model->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"
                                    {{ old('status', $model->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
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
@endsection
