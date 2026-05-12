<div class="order-card">

    <div class="d-flex justify-content-between">

        <div>

            <div class="order-code">
                {{ $item->order->code }}
            </div>

            <div class="order-table">
                Table : {{ $item->order->table_code }}
            </div>

        </div>

        <div>

            <span class="badge-kds badge-{{ $item->kitchen_status }}">
                {{ strtoupper($item->kitchen_status) }}
            </span>

        </div>

    </div>

    <div class="order-menu">

        {{ $item->menuItem->name ?? '-' }} 

    </div>

    <div class="order-qty">

        Qty : {{ $item->qty }}

    </div>

    @if($item->note)

        <div class="order-note">

            {{ $item->note }}

        </div>

    @endif

    <div class="d-flex justify-content-between align-items-center mt-3">

        <div class="kds-time">
            {{ $item->created_at->diffForHumans() }}
        </div>

    </div>

    <div class="mt-3">

        @if($item->kitchen_status == 'new')

            <button
                class="kds-btn btn-start update-status"
                data-id="{{ $item->id }}"
                data-status="cook">

                Start Cooking

            </button>

        @elseif($item->kitchen_status == 'cook')

            <button
                class="kds-btn btn-ready update-status"
                data-id="{{ $item->id }}"
                data-status="ready">

                Mark Ready

            </button>

        @elseif($item->kitchen_status == 'ready')

            <button
                class="kds-btn btn-served update-status"
                data-id="{{ $item->id }}"
                data-status="served">

                Served

            </button>

        @endif

    </div>

</div>