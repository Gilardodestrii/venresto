@extends('layouts.admin')

@section('content')

<meta name="kitchen-base-url" content="{{ url($tenant->slug.'/admin/kitchen') }}">

{{-- ===== KDS HEADER ===== --}}
<div class="kds-header sticky top-0 z-50">
    <div class="kds-header__inner">

        {{-- Title + subtitle --}}
        <div class="kds-header__title">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Kitchen Display</span>
            </div>
            <p class="kds-header__subtitle">Realtime order monitoring</p>
        </div>

        {{-- Controls --}}
        <div class="kds-header__controls">
            {{-- Stats badges --}}
            <div class="kds-stats hidden sm:flex">
                <span class="kds-stat kds-stat--pending" id="stat-pending">
                    <span class="kds-stat__dot"></span>
                    <span id="count-pending">{{ $items->where('kitchen_status','new')->count() }}</span> Pending
                </span>
                <span class="kds-stat kds-stat--cooking" id="stat-cooking">
                    <span class="kds-stat__dot kds-stat__dot--pulse"></span>
                    <span id="count-cooking">{{ $items->where('kitchen_status','cook')->count() }}</span> Cooking
                </span>
                <span class="kds-stat kds-stat--ready" id="stat-ready">
                    <span class="kds-stat__dot"></span>
                    <span id="count-ready">{{ $items->where('kitchen_status','ready')->count() }}</span> Ready
                </span>
            </div>

            {{-- LIVE badge --}}
            <span class="kds-live-badge" id="live-badge">
                <span class="kds-live-badge__dot"></span>
                LIVE
            </span>

            {{-- Fullscreen --}}
            <button class="kds-btn-icon" id="fullscreen-btn" title="Toggle Fullscreen">
                <svg id="fs-icon-enter" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                <svg id="fs-icon-exit" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 9V4H4v5h5zm6 0h5V4h-5v5zm-6 6H4v5h5v-5zm6 6h5v-5h-5v5z" />
                </svg>
            </button>
        </div>

    </div>
</div>

{{-- ===== KDS BOARD ===== --}}
<div class="kds-board" id="kds-board">

    {{-- PENDING COLUMN --}}
    <div class="kds-column kds-column--pending">
        <div class="kds-column__header">
            <div class="kds-column__header-left">
                <span class="kds-column__indicator kds-column__indicator--pending"></span>
                <span class="kds-column__title">Pending</span>
            </div>
            <span class="kds-column__badge kds-column__badge--pending" id="badge-pending">
                {{ $items->where('kitchen_status','new')->count() }}
            </span>
        </div>
        <div class="kds-column__body" id="pending-list">
            @foreach($items->where('kitchen_status','new') as $item)
                @include('admin.kitchen.partials.card')
            @endforeach
            @if($items->where('kitchen_status','new')->isEmpty())
                <div class="kds-empty" id="empty-pending">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p>No pending orders</p>
                </div>
            @endif
        </div>
    </div>

    {{-- COOKING COLUMN --}}
    <div class="kds-column kds-column--cooking">
        <div class="kds-column__header">
            <div class="kds-column__header-left">
                <span class="kds-column__indicator kds-column__indicator--cooking kds-column__indicator--pulse"></span>
                <span class="kds-column__title">Cooking</span>
            </div>
            <span class="kds-column__badge kds-column__badge--cooking" id="badge-cooking">
                {{ $items->where('kitchen_status','cook')->count() }}
            </span>
        </div>
        <div class="kds-column__body" id="cooking-list">
            @foreach($items->where('kitchen_status','cook') as $item)
                @include('admin.kitchen.partials.card')
            @endforeach
            @if($items->where('kitchen_status','cook')->isEmpty())
                <div class="kds-empty" id="empty-cooking">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                    <p>Nothing cooking</p>
                </div>
            @endif
        </div>
    </div>

    {{-- READY COLUMN --}}
    <div class="kds-column kds-column--ready">
        <div class="kds-column__header">
            <div class="kds-column__header-left">
                <span class="kds-column__indicator kds-column__indicator--ready"></span>
                <span class="kds-column__title">Ready to Serve</span>
            </div>
            <span class="kds-column__badge kds-column__badge--ready" id="badge-ready">
                {{ $items->where('kitchen_status','ready')->count() }}
            </span>
        </div>
        <div class="kds-column__body" id="ready-list">
            @foreach($items->where('kitchen_status','ready') as $item)
                @include('admin.kitchen.partials.card')
            @endforeach
            @if($items->where('kitchen_status','ready')->isEmpty())
                <div class="kds-empty" id="empty-ready">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M5 13l4 4L19 7" />
                    </svg>
                    <p>No items ready</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ===== KDS STYLES ===== --}}
<style>
/* ---- Reset & Base ----------------------------------------- */
.kds-board, .kds-column, .kds-card { box-sizing: border-box; }

/* ---- Header ----------------------------------------------- */
.kds-header {
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid #f1f5f9;
    padding: 12px 16px;
}
.kds-header__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    max-width: 1600px;
    margin: 0 auto;
}
.kds-header__title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.kds-header__subtitle {
    font-size: 12px;
    font-weight: 400;
    color: #9ca3af;
    margin: 0;
}
.kds-header__controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

/* ---- Stats ------------------------------------------------- */
.kds-stats {
    display: flex;
    gap: 6px;
    align-items: center;
}
.kds-stat {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.kds-stat--pending  { background: #fef3c7; color: #92400e; }
.kds-stat--cooking  { background: #dbeafe; color: #1e40af; }
.kds-stat--ready    { background: #d1fae5; color: #065f46; }
.kds-stat__dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
}
.kds-stat__dot--pulse { animation: kdsPulse 1.4s infinite; }

/* ---- LIVE badge -------------------------------------------- */
.kds-live-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    background: #dc2626;
    color: #fff;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
}
.kds-live-badge__dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #fff;
    animation: kdsPulse 1.4s infinite;
}
.kds-live-badge--offline { background: #6b7280; }
.kds-live-badge--offline .kds-live-badge__dot { animation: none; }

/* ---- Icon button ------------------------------------------- */
.kds-btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    color: #374151;
    transition: background .15s, color .15s;
}
.kds-btn-icon:hover { background: #e2e8f0; }

/* ---- Board (3-column kanban) ------------------------------- */
.kds-board {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    padding: 16px;
    max-width: 1600px;
    margin: 0 auto;
    min-height: calc(100vh - 70px);
}
@media (min-width: 768px) {
    .kds-board { grid-template-columns: repeat(3, 1fr); padding: 20px; gap: 16px; }
    .kds-header { padding: 14px 20px; }
    .kds-header__title { font-size: 22px; }
}
@media (min-width: 1280px) {
    .kds-board { padding: 24px; gap: 20px; }
}

/* ---- Column ----------------------------------------------- */
.kds-column {
    display: flex;
    flex-direction: column;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    min-height: 200px;
}
.kds-column--pending { border-top: 3px solid #f59e0b; }
.kds-column--cooking { border-top: 3px solid #3b82f6; }
.kds-column--ready   { border-top: 3px solid #10b981; }

.kds-column__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
}
.kds-column__header-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.kds-column__title {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.kds-column__indicator {
    width: 10px; height: 10px;
    border-radius: 50%;
}
.kds-column__indicator--pending { background: #f59e0b; }
.kds-column__indicator--cooking { background: #3b82f6; }
.kds-column__indicator--ready   { background: #10b981; }
.kds-column__indicator--pulse   { animation: kdsPulse 1.4s infinite; }

.kds-column__badge {
    min-width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    padding: 0 7px;
}
.kds-column__badge--pending { background: #fef3c7; color: #92400e; }
.kds-column__badge--cooking { background: #dbeafe; color: #1e40af; }
.kds-column__badge--ready   { background: #d1fae5; color: #065f46; }

.kds-column__body {
    flex: 1;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    max-height: calc(100vh - 160px);
}
@media (min-width: 768px) {
    .kds-column__body { max-height: calc(100vh - 140px); }
}

/* ---- Empty state ------------------------------------------ */
.kds-empty {
    text-align: center;
    padding: 32px 16px;
    color: #9ca3af;
    font-size: 13px;
}

/* ---- Animations ------------------------------------------- */
@keyframes kdsPulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: .3; }
}
@keyframes kdsSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.kds-card-enter {
    animation: kdsSlideIn .25s ease-out;
}

/* ---- Fullscreen tweaks ------------------------------------ */
:fullscreen .kds-header,
:-webkit-full-screen .kds-header {
    background: #111827;
    border-bottom-color: #374151;
}
:fullscreen .kds-header__title,
:-webkit-full-screen .kds-header__title { color: #f9fafb; }
:fullscreen .kds-board,
:-webkit-full-screen .kds-board { background: #0f172a; min-height: 100vh; }
:fullscreen .kds-column,
:-webkit-full-screen .kds-column { background: #1e293b; border-color: #334155; }
:fullscreen .kds-column__header,
:-webkit-full-screen .kds-column__header { background: #1e293b; border-bottom-color: #334155; }
:fullscreen .kds-column__title,
:-webkit-full-screen .kds-column__title { color: #f1f5f9; }
:fullscreen .kds-btn-icon,
:-webkit-full-screen .kds-btn-icon { background: #334155; border-color: #475569; color: #f1f5f9; }
</style>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const baseUrl = document
        .querySelector('meta[name="kitchen-base-url"]')
        .getAttribute('content');

    // ---- Fullscreen toggle ----
    const fsBtn       = document.getElementById('fullscreen-btn');
    const fsIconEnter = document.getElementById('fs-icon-enter');
    const fsIconExit  = document.getElementById('fs-icon-exit');

    fsBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    });
    document.addEventListener('fullscreenchange', () => {
        const isFs = !!document.fullscreenElement;
        fsIconEnter.classList.toggle('hidden',  isFs);
        fsIconExit.classList.toggle('hidden', !isFs);
        fsBtn.title = isFs ? 'Exit Fullscreen' : 'Toggle Fullscreen';
    });

    // ---- LIVE / offline indicator ----
    const liveBadge = document.getElementById('live-badge');
    function setOnline()  { liveBadge.classList.remove('kds-live-badge--offline'); liveBadge.querySelector('.kds-live-badge__dot').style.animation = ''; }
    function setOffline() { liveBadge.classList.add('kds-live-badge--offline');    liveBadge.querySelector('.kds-live-badge__dot').style.animation = 'none'; }

    // ---- Status update ----
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.update-status');
        if (!btn) return;

        const id     = btn.dataset.id;
        const status = btn.dataset.status;
        btn.disabled = true;
        btn.style.opacity = '0.6';

        try {
            const res = await fetch(`${baseUrl}/item/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            });
            const data = await res.json();
            if (data.success) {
                // Remove card with animation instead of full reload
                const card = btn.closest('.kds-card');
                if (card) {
                    card.style.transition = 'opacity .2s, transform .2s';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.95)';
                    setTimeout(() => { card.remove(); refreshCounts(); }, 220);
                }
            } else {
                alert('Failed to update status');
                btn.disabled = false;
                btn.style.opacity = '';
            }
        } catch (err) {
            console.error(err);
            alert('Server error');
            btn.disabled = false;
            btn.style.opacity = '';
        }
    });

    // ---- Count badges ----
    function refreshCounts() {
        ['pending', 'cooking', 'ready'].forEach(col => {
            const listId = col === 'cooking' ? 'cooking-list' : col === 'ready' ? 'ready-list' : 'pending-list';
            const count  = document.getElementById(listId).querySelectorAll('.kds-card').length;
            const badge  = document.getElementById('badge-' + col);
            const stat   = document.getElementById('count-' + col);
            if (badge) badge.textContent = count;
            if (stat)  stat.textContent  = count;
        });
    }

    // ---- Poll for new items (5s) ----
    let lastHash = '';
    async function pollKitchen() {
        try {
            const res = await fetch(`${baseUrl}/live`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) { setOffline(); return; }
            setOnline();
            const data = await res.json();
            const hash = JSON.stringify(data);
            if (hash !== lastHash) {
                lastHash = hash;
                location.reload();
            }
        } catch (e) {
            setOffline();
        }
    }

    setInterval(pollKitchen, 5000);

    // ---- Elapsed time on cards ----
    function updateTimers() {
        document.querySelectorAll('[data-created-at]').forEach(el => {
            const created = new Date(el.dataset.createdAt);
            const diff    = Math.floor((Date.now() - created) / 1000);
            const m = Math.floor(diff / 60);
            const s = diff % 60;
            el.textContent = m > 0 ? `${m}m ${s}s` : `${s}s`;
            // Urgency colors
            const parent = el.closest('.kds-card');
            if (parent) {
                parent.classList.toggle('kds-card--urgent',  m >= 10);
                parent.classList.toggle('kds-card--warning', m >= 5 && m < 10);
            }
        });
    }
    setInterval(updateTimers, 1000);
    updateTimers();

})();
</script>
@endpush
