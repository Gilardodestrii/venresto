@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-3">

    <div class="flex justify-between items-center mb-4">
        <div>
            <h4 class="font-bold mb-0">Cashier Shift</h4>
            <small class="text-gray-500">Open & close cashier session</small>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 rounded-xl p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-800 rounded-xl p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="lg:col-span-4">

            <div class="bg-white rounded-2xl shadow-sm border-0 p-6">

                @if(!$activeSession)

                    <h6 class="font-bold mb-3">Open Shift</h6>

                    <form method="POST"
                          action="{{ route('tenant.admin.cashier-sessions.open', $currentTenant->slug) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opening Cash</label>

                            <input type="number"
                                   name="opening_cash"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   min="0"
                                   required>
                        </div>

                        <button class="w-full bg-indigo-600 text-white rounded-lg py-2 px-4 hover:bg-indigo-700 transition-colors">
                            Open Shift
                        </button>
                    </form>

                @else

                    <h6 class="font-bold mb-3">Active Shift</h6>

                    <div class="mb-3">
                        <small class="text-gray-500 block">Opened At</small>
                        <div>{{ $activeSession->opened_at }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-gray-500 block">Opening Cash</small>
                        <div>Rp {{ number_format($activeSession->opening_cash,0,',','.') }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-gray-500 block">Sales Total</small>
                        <div>Rp {{ number_format($activePaymentTotal,0,',','.') }}</div>
                    </div>

                    <form method="POST"
                          action="{{ route('tenant.admin.cashier-sessions.close', [$currentTenant->slug, $activeSession->id]) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Closing Cash</label>

                            <input type="number"
                                   name="closing_cash"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   min="0"
                                   required>
                        </div>

                        <button class="w-full bg-red-600 text-white rounded-lg py-2 px-4 hover:bg-red-700 transition-colors">
                            Close Shift
                        </button>
                    </form>

                @endif

            </div>

        </div>

        <div class="lg:col-span-8">

            <div class="bg-white rounded-2xl shadow-sm border-0 p-6">

                <h6 class="font-bold mb-3">Shift History</h6>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-200">
                                <th class="pb-3 font-medium">Cashier</th>
                                <th class="pb-3 font-medium">Opening</th>
                                <th class="pb-3 font-medium">Expected</th>
                                <th class="pb-3 font-medium">Closing</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($sessions as $session)
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-3">{{ $session->cashier->name ?? '-' }}</td>
                                    <td class="py-3">Rp {{ number_format($session->opening_cash,0,',','.') }}</td>
                                    <td class="py-3">Rp {{ number_format($session->expected_cash,0,',','.') }}</td>
                                    <td class="py-3">Rp {{ number_format($session->closing_cash,0,',','.') }}</td>
                                    <td class="py-3">
                                        @if($session->status == 'open')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Open</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Closed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-8">
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
@endsection
