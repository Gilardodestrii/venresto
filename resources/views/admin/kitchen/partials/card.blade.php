<div class="bg-white border border-gray-100 rounded-2xl p-4 mb-4 shadow-md shadow-gray-200/40 transition duration-200 hover:-translate-y-0.5">

    <div class="flex justify-between">

        <div>

            <div class="text-lg font-bold text-gray-900">
                {{ $item->order->code }}
            </div>

            <div class="text-gray-500 text-sm">
                Table : {{ $item->order->table_code }}
            </div>

        </div>

        <div>

            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($item->kitchen_status == 'new') bg-amber-100 text-amber-800
                @elseif($item->kitchen_status == 'cook') bg-blue-100 text-blue-800
                @elseif($item->kitchen_status == 'ready') bg-green-100 text-green-800
                @endif">
                {{ strtoupper($item->kitchen_status) }}
            </span>

        </div>

    </div>

    <div class="text-xl font-bold text-gray-900 mt-3">

        {{ $item->menuItem->name ?? '-' }}

    </div>

    <div class="text-sm mt-1.5 text-gray-700">

        Qty : {{ $item->qty }}

    </div>

    @if($item->note)

        <div class="mt-3 bg-orange-50 text-orange-600 rounded-xl p-3 text-xs">

            {{ $item->note }}

        </div>

    @endif

    <div class="flex justify-between items-center mt-3">

        <div class="text-xs text-gray-500">
            {{ $item->created_at->diffForHumans() }}
        </div>

    </div>

    <div class="mt-3">

        @if($item->kitchen_status == 'new')

            <button
                class="w-full rounded-2xl p-3 font-semibold bg-sky-500 text-white hover:-translate-y-0.5 transition duration-200 update-status"
                data-id="{{ $item->id }}"
                data-status="cook">

                Start Cooking

            </button>

        @elseif($item->kitchen_status == 'cook')

            <button
                class="w-full rounded-2xl p-3 font-semibold bg-emerald-500 text-white hover:-translate-y-0.5 transition duration-200 update-status"
                data-id="{{ $item->id }}"
                data-status="ready">

                Mark Ready

            </button>

        @elseif($item->kitchen_status == 'ready')

            <button
                class="w-full rounded-2xl p-3 font-semibold bg-gray-900 text-white hover:-translate-y-0.5 transition duration-200 update-status"
                data-id="{{ $item->id }}"
                data-status="served">

                Served

            </button>

        @endif

    </div>

</div>