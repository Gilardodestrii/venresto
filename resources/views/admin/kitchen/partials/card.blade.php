{{--
    KDS Order Card
    Props: $item (KitchenItem model)
    Statuses: new | cook | ready
--}}
<div class="kds-card kds-card--{{ $item->kitchen_status }} kds-card-enter"
     data-id="{{ $item->id }}"
     data-status="{{ $item->kitchen_status }}">

    {{-- ── TOP ROW: order code + status badge ── --}}
    <div class="kds-card__header">

        <div class="kds-card__order">
            <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="kds-card__order-code"># {{ $item->order->code }}</span>
        </div>

        <span class="kds-card__status-badge kds-card__status-badge--{{ $item->kitchen_status }}">
            @if($item->kitchen_status === 'new')
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    <circle cx="12" cy="12" r="9" stroke-width="2" />
                </svg>
                NEW
            @elseif($item->kitchen_status === 'cook')
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                </svg>
                COOKING
            @elseif($item->kitchen_status === 'ready')
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                READY
            @endif
        </span>

    </div>

    {{-- ── DIVIDER ── --}}
    <div class="kds-card__divider"></div>

    {{-- ── MENU ITEM ── --}}
    <div class="kds-card__body">

        <div class="kds-card__item-name">{{ $item->menuItem->name ?? '-' }}</div>

        {{-- Table + Qty row --}}
        <div class="kds-card__meta">
            <span class="kds-card__meta-pill kds-card__meta-pill--table">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M3 14h18M10 4v16M14 4v16" />
                </svg>
                Table {{ $item->order->table_code }}
            </span>
            <span class="kds-card__meta-pill kds-card__meta-pill--qty">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
                Qty {{ $item->qty }}
            </span>
        </div>

        {{-- Note --}}
        @if($item->note)
        <div class="kds-card__note">
            <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            <span>{{ $item->note }}</span>
        </div>
        @endif

    </div>

    {{-- ── FOOTER: timer + action button ── --}}
    <div class="kds-card__footer">

        <div class="kds-card__timer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span data-created-at="{{ $item->created_at->toIso8601String() }}">
                {{ $item->created_at->diffForHumans() }}
            </span>
        </div>

        @if($item->kitchen_status === 'new')
            <button class="kds-card__action kds-card__action--cook update-status"
                    data-id="{{ $item->id }}" data-status="cook">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Start Cooking
            </button>

        @elseif($item->kitchen_status === 'cook')
            <button class="kds-card__action kds-card__action--ready update-status"
                    data-id="{{ $item->id }}" data-status="ready">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mark Ready
            </button>

        @elseif($item->kitchen_status === 'ready')
            <button class="kds-card__action kds-card__action--served update-status"
                    data-id="{{ $item->id }}" data-status="served">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                Served
            </button>
        @endif

    </div>

</div>

{{-- ===== CARD STYLES (loaded once via Blade include cache) ===== --}}
@once
<style>
/* ---- Card base -------------------------------------------- */
.kds-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    transition: box-shadow .2s, border-color .2s;
    position: relative;
}
.kds-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }

/* Left accent bar */
.kds-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 14px 0 0 14px;
}
.kds-card--new::before  { background: #f59e0b; }
.kds-card--cook::before { background: #3b82f6; }
.kds-card--ready::before { background: #10b981; }

/* Urgency states */
.kds-card--warning { border-color: #fbbf24; background: #fffbeb; }
.kds-card--urgent  { border-color: #ef4444; background: #fff5f5; animation: kdsUrgentPulse 2s infinite; }
@keyframes kdsUrgentPulse {
    0%, 100% { border-color: #ef4444; }
    50%       { border-color: #fca5a5; }
}

/* ---- Card header ------------------------------------------ */
.kds-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px 8px;
    gap: 8px;
}
.kds-card__order {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6b7280;
}
.kds-card__order-code {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    font-family: ui-monospace, monospace;
}

/* Status badge */
.kds-card__status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .05em;
    white-space: nowrap;
}
.kds-card__status-badge--new   { background: #fef3c7; color: #92400e; }
.kds-card__status-badge--cook  { background: #dbeafe; color: #1e40af; }
.kds-card__status-badge--ready { background: #d1fae5; color: #065f46; }

/* ---- Divider ---------------------------------------------- */
.kds-card__divider {
    height: 1px;
    background: #f1f5f9;
    margin: 0 12px;
}

/* ---- Card body -------------------------------------------- */
.kds-card__body {
    padding: 10px 12px 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.kds-card__item-name {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
}

/* Meta pills */
.kds-card__meta {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.kds-card__meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
}
.kds-card__meta-pill--table { background: #f1f5f9; color: #374151; }
.kds-card__meta-pill--qty   { background: #ede9fe; color: #5b21b6; }

/* Note */
.kds-card__note {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 12px;
    color: #c2410c;
    line-height: 1.4;
}

/* ---- Card footer ------------------------------------------ */
.kds-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px 12px;
    gap: 8px;
}
.kds-card__timer {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #9ca3af;
    font-variant-numeric: tabular-nums;
}
.kds-card--warning .kds-card__timer { color: #d97706; font-weight: 600; }
.kds-card--urgent  .kds-card__timer { color: #dc2626; font-weight: 700; }

/* Action buttons */
.kds-card__action {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: opacity .15s, transform .1s;
    white-space: nowrap;
}
.kds-card__action:hover   { opacity: .9; transform: translateY(-1px); }
.kds-card__action:active  { transform: translateY(0); }
.kds-card__action:disabled { opacity: .5; cursor: not-allowed; transform: none; }

.kds-card__action--cook   { background: #3b82f6; color: #fff; }
.kds-card__action--ready  { background: #10b981; color: #fff; }
.kds-card__action--served { background: #111827; color: #fff; }

/* ---- Dark mode (fullscreen) ------------------------------- */
:fullscreen .kds-card,
:-webkit-full-screen .kds-card {
    background: #1e293b;
    border-color: #334155;
}
:fullscreen .kds-card__divider,
:-webkit-full-screen .kds-card__divider { background: #334155; }
:fullscreen .kds-card__order-code,
:-webkit-full-screen .kds-card__order-code { color: #94a3b8; }
:fullscreen .kds-card__item-name,
:-webkit-full-screen .kds-card__item-name { color: #f1f5f9; }
:fullscreen .kds-card__meta-pill--table,
:-webkit-full-screen .kds-card__meta-pill--table { background: #334155; color: #cbd5e1; }
:fullscreen .kds-card__timer,
:-webkit-full-screen .kds-card__timer { color: #64748b; }

/* ---- Card error banner (inline) --------------------------- */
.kds-card__error {
    background: #fef2f2;
    border-top: 1px solid #fecaca;
    color: #b91c1c;
    font-size: 11px;
    font-weight: 500;
    padding: 6px 12px;
    text-align: center;
    line-height: 1.3;
}
</style>
@endonce
