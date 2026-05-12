@extends('layouts.admin')

@section('page-title', 'Edit Outlet')

@section('content')

<div class="container-fluid px-0">

    <div class="row justify-content-center">

        <div class="col-xl-7">

            <div class="premium-form-card">

                {{-- HEADER --}}
                <div class="form-header">

                    <div>

                        <h3 class="fw-bold mb-1">
                            Edit Outlet
                        </h3>

                        <p class="text-muted mb-0">
                            Perbarui informasi outlet restoran
                        </p>

                    </div>

                </div>

                {{-- FORM --}}
                <form method="POST"
                      action="{{ route('tenant.admin.outlets.update', [$currentTenant->slug, $outlet->id]) }}">

                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        {{-- NAME --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Nama Outlet
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $outlet->name) }}"
                                   class="form-control premium-input @error('name') is-invalid @enderror">

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        {{-- ADDRESS --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Alamat Outlet
                            </label>

                            <textarea name="address"
                                      rows="5"
                                      class="form-control premium-input @error('address') is-invalid @enderror">{{ old('address', $outlet->address) }}</textarea>

                            @error('address')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="form-footer">

                        <a href="{{ route('tenant.admin.outlets.index', $currentTenant->slug) }}"
                           class="btn btn-light-premium">

                            Kembali

                        </a>

                        <button class="btn btn-primary-premium">

                            <i class="bi bi-check-circle me-1"></i>
                            Update Outlet

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

.premium-form-card{
    background:white;

    border-radius:30px;

    overflow:hidden;

    border:1px solid #eef2f7;

    box-shadow:
        0 15px 40px rgba(15,23,42,.05);
}

.form-header{
    padding:32px 36px;

    border-bottom:1px solid #f1f5f9;
}

.form-body{
    padding:36px;
}

.form-footer{
    padding:24px 36px;

    border-top:1px solid #f1f5f9;

    display:flex;
    justify-content:space-between;
}

</style>

@endpush