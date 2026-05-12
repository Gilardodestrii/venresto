@extends('layouts.admin')

@section('page-title', 'Detail Outlet')

@section('content')

<div class="container-fluid px-0">

    <div class="row justify-content-center">

        <div class="col-xl-8">

            <div class="premium-detail-card">

                {{-- HEADER --}}
                <div class="detail-header">

                    <div class="outlet-big-icon">

                        <i class="bi bi-shop"></i>

                    </div>

                    <div>

                        <h2 class="fw-bold mb-1">
                            {{ $outlet->name }}
                        </h2>

                        <div class="text-muted">
                            Detail informasi outlet restoran
                        </div>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="detail-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="info-card">

                                <div class="info-label">
                                    Nama Outlet
                                </div>

                                <div class="info-value">
                                    {{ $outlet->name }}
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-card">

                                <div class="info-label">
                                    Status
                                </div>

                                <div class="info-value">

                                    @if($outlet->is_active)

                                        <span class="badge-premium success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge-premium danger">
                                            Non Active
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-12">

                            <div class="info-card">

                                <div class="info-label">
                                    Alamat Outlet
                                </div>

                                <div class="info-value">
                                    {{ $outlet->address ?? '-' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="detail-footer">

                    <a href="{{ route('tenant.admin.outlets.index', $currentTenant->slug) }}"
                       class="btn btn-light-premium">

                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali

                    </a>

                    <a href="{{ route('tenant.admin.outlets.edit', [$currentTenant->slug, $outlet->id]) }}"
                       class="btn btn-primary-premium">

                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Outlet

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

.premium-detail-card{
    background:white;

    border-radius:30px;

    overflow:hidden;

    border:1px solid #eef2f7;

    box-shadow:
        0 15px 40px rgba(15,23,42,.05);
}

.detail-header{
    padding:40px;

    background:
        linear-gradient(
            135deg,
            rgba(14,165,233,.08),
            rgba(37,99,235,.04)
        );

    display:flex;
    align-items:center;
    gap:24px;
}

.outlet-big-icon{
    width:90px;
    height:90px;

    border-radius:28px;

    background:white;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:36px;

    color:var(--primary);

    box-shadow:
        0 10px 25px rgba(14,165,233,.10);
}

.detail-body{
    padding:40px;
}

.info-card{
    background:#f8fafc;

    border-radius:22px;

    padding:24px;
}

.info-label{
    font-size:13px;

    color:#64748b;

    margin-bottom:10px;
}

.info-value{
    font-size:16px;
    font-weight:600;
}

.detail-footer{
    padding:24px 40px;

    border-top:1px solid #f1f5f9;

    display:flex;
    justify-content:space-between;
    gap:12px;
}

@media(max-width:768px){

    .detail-header{
        flex-direction:column;
        text-align:center;
    }

    .detail-footer{
        flex-direction:column;
    }

}

</style>

@endpush