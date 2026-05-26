@extends('layouts.admin')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Cashier Shift</h4>
            <small class="text-muted">Open & close cashier session</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    @if(!$activeSession)

                        <h6 class="fw-bold mb-3">Open Shift</h6>

                        <form method="POST"
                              action="{{ route('tenant.admin.cashier-sessions.open', $currentTenant->slug) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Opening Cash</label>

                                <input type="number"
                                       name="opening_cash"
                                       class="form-control rounded-3"
                                       min="0"
                                       required>
                            </div>

                            <button class="btn btn-primary w-100 rounded-3">
                                Open Shift
                            </button>
                        </form>

                    @else

                        <h6 class="fw-bold mb-3">Active Shift</h6>

                        <div class="mb-3">
                            <small class="text-muted d-block">Opened At</small>
                            <div>{{ $activeSession->opened_at }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Opening Cash</small>
                            <div>Rp {{ number_format($activeSession->opening_cash,0,',','.') }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Sales Total</small>
                            <div>Rp {{ number_format($activePaymentTotal,0,',','.') }}</div>
                        </div>

                        <form method="POST"
                              action="{{ route('tenant.admin.cashier-sessions.close', [$currentTenant->slug, $activeSession->id]) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Closing Cash</label>

                                <input type="number"
                                       name="closing_cash"
                                       class="form-control rounded-3"
                                       min="0"
                                       required>
                            </div>

                            <button class="btn btn-danger w-100 rounded-3">
                                Close Shift
                            </button>
                        </form>

                    @endif

                </div>
            </div>

        </div>

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    <h6 class="fw-bold mb-3">Shift History</h6>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Cashier</th>
                                    <th>Opening</th>
                                    <th>Expected</th>
                                    <th>Closing</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>{{ $session->cashier->name ?? '-' }}</td>
                                        <td>Rp {{ number_format($session->opening_cash,0,',','.') }}</td>
                                        <td>Rp {{ number_format($session->expected_cash,0,',','.') }}</td>
                                        <td>Rp {{ number_format($session->closing_cash,0,',','.') }}</td>
                                        <td>
                                            @if($session->status == 'open')
                                                <span class="badge bg-success">Open</span>
                                            @else
                                                <span class="badge bg-secondary">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada cashier shift.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $sessions->links() }}

                </div>
            </div>

        </div>

    </div>

</div>
@endsection
