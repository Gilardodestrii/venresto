@extends('layouts.admin')

@section('content')

<style>

body{
    background:#f4f7fb;
}

/* HEADER */

.kds-header{
    position:sticky;
    top:0;
    z-index:50;
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(20px);
    /* border-bottom:1px solid #e5e7eb; */
    padding:16px 20px;
}

.kds-title{
    font-size:26px;
    font-weight:700;
}

/* BOARD */

.kds-board{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:20px;
}

.kds-column{
    background:white;
    border-radius:24px;
    padding:16px;
    min-height:70vh;
    box-shadow:
        0 10px 30px rgba(0,0,0,.05);
}

.column-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:16px;
}

.column-title{
    font-weight:700;
    font-size:18px;
}

/* CARD */

.order-card{
    background:#fff;
    border:1px solid #eef2f7;
    border-radius:22px;
    padding:18px;
    margin-bottom:16px;
    transition:.2s;
    box-shadow:
        0 5px 20px rgba(0,0,0,.04);
}

.order-card:hover{
    transform:translateY(-2px);
}

.order-code{
    font-size:18px;
    font-weight:700;
}

.order-table{
    color:#6b7280;
    font-size:14px;
}

.order-menu{
    font-size:22px;
    font-weight:700;
    margin-top:12px;
}

.order-qty{
    font-size:15px;
    margin-top:6px;
    color:#374151;
}

.order-note{
    margin-top:10px;
    background:#fff7ed;
    color:#c2410c;
    border-radius:14px;
    padding:10px;
    font-size:13px;
}

.kds-time{
    font-size:13px;
    color:#6b7280;
}

/* BUTTON */

.kds-btn{
    width:100%;
    border:none;
    border-radius:16px;
    padding:12px;
    font-weight:600;
    transition:.2s;
}

.btn-start{
    background:#0ea5e9;
    color:white;
}

.btn-ready{
    background:#10b981;
    color:white;
}

.btn-served{
    background:#111827;
    color:white;
}

.kds-btn:hover{
    transform:translateY(-2px);
}

/* STATUS */

.badge-kds{
    border-radius:999px;
    padding:6px 12px;
    font-size:12px;
    font-weight:600;
}

.badge-new{
    background:#fef3c7;
    color:#92400e;
}

.badge-cook{
    background:#dbeafe;
    color:#1d4ed8;
}

.badge-ready{
    background:#dcfce7;
    color:#166534;
}

/* MOBILE */

@media(max-width:1200px){

    .kds-board{
        grid-template-columns:1fr;
    }

}

</style>
<meta name="kitchen-base-url"
      content="{{ url($tenant->slug.'/admin/kitchen') }}">

<div class="kds-header d-flex justify-content-between align-items-center">

    <div>
        <div class="kds-title">
            Kitchen Display
        </div>

        <div class="text-muted">
            Realtime kitchen order monitoring
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-dark rounded-pill"
                id="fullscreen-btn">

            <i class="bi bi-arrows-fullscreen"></i>

            Fullscreen

        </button>
        <div class="badge bg-primary">
            LIVE
        </div>

    </div>

</div>

<div class="container-fluid py-4">

    <div class="kds-board">

        {{-- PENDING --}}
        <div class="kds-column">

            <div class="column-header">

                <div class="column-title">
                    Pending
                </div>

            </div>

            <div id="pending-list">

                @foreach($items->where('kitchen_status','new') as $item)

                    @include('admin.kitchen.partials.card')

                @endforeach

            </div>

        </div>

        {{-- COOKING --}}
        <div class="kds-column">

            <div class="column-header">

                <div class="column-title">
                    Cooking
                </div>

            </div>

            <div id="cooking-list">

                @foreach($items->where('kitchen_status','cook') as $item)

                    @include('admin.kitchen.partials.card')

                @endforeach

            </div>

        </div>

        {{-- READY --}}
        <div class="kds-column">

            <div class="column-header">

                <div class="column-title">
                    Ready
                </div>

            </div>

            <div id="ready-list">

                @foreach($items->where('kitchen_status','ready') as $item)

                    @include('admin.kitchen.partials.card')

                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

    document
    .getElementById('fullscreen-btn')
    .addEventListener('click', () => {

        if(!document.fullscreenElement){

            document.documentElement.requestFullscreen();

        } else {

            document.exitFullscreen();

        }

    });

const kitchenBaseUrl = document
    .querySelector('meta[name="kitchen-base-url"]')
    .getAttribute('content');

async function loadKitchen()
{
    try {

        const response = await fetch(
            `${kitchenBaseUrl}/live`,
            {
                headers:{
                    'Accept':'application/json'
                }
            }
        );

        if(response.ok){

            location.reload();

        }

    } catch(error){

        console.log(error);

    }
}

// realtime refresh
setInterval(loadKitchen, 5000);

document.addEventListener('click', async function(e){

    const button = e.target.closest('.update-status');

    if(!button) return;

    const id = button.dataset.id;
    const status = button.dataset.status;

    button.disabled = true;

    try {

        const response = await fetch(
            `${kitchenBaseUrl}/item/${id}/status`,
            {
                method:'POST',

                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },

                body:JSON.stringify({
                    status:status
                })
            }
        );

        const result = await response.json();

        if(result.success){

            location.reload();

        } else {

            alert('Failed update status');

        }

    } catch(error){

        console.log(error);

        alert('Server error');

    } finally {

        button.disabled = false;

    }

});

</script>

@endpush