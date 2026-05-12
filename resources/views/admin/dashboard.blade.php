@extends('layouts.admin')

@section('page-title','Dashboard')

@section('content')

@php
    $tenantId = \App\Services\TenantContext::get()?->id;

    $users = \App\Models\User::where('tenant_id',$tenantId)->count();
    $orders = \App\Models\Order::where('tenant_id',$tenantId)->count();
    $revenue = \App\Models\Order::where('tenant_id',$tenantId)
        ->where('status','paid')
        ->sum('grand_total');
    $menu = \App\Models\MenuItem::where('tenant_id',$tenantId)->count();
@endphp

<div class="row g-3">

    <div class="col-md-3">
        <div class="card-premium p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Users</small>
                    <h4 class="fw-bold">{{ $users }}</h4>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Orders</small>
                    <h4 class="fw-bold">{{ $orders }}</h4>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-journal"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Revenue</small>
                    <h4 class="fw-bold text-primary-soft">
                        Rp {{ number_format($revenue,0,',','.') }}
                    </h4>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Menu</small>
                    <h4 class="fw-bold">{{ $menu }}</h4>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHART --}}
<div class="card-premium mt-4 p-4">
    <h6 class="mb-3">Sales Overview</h6>
    <canvas id="chart"></canvas>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chart'), {
    type:'line',
    data:{
        labels:['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
        datasets:[{
            label:'Sales',
            data:[12,19,3,5,2,30,45],
            borderColor:'#0ea5e9',
            tension:.4
        }]
    }
});
</script>
@endpush