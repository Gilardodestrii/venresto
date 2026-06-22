@extends('layouts.admin')

@section('content')

<meta name="kitchen-base-url"
      content="{{ url($tenant->slug.'/admin/kitchen') }}">

<div class="sticky top-0 z-50 bg-white/85 backdrop-blur-xl px-5 py-4">

    <div class="flex justify-between items-center">

        <div>
            <div class="text-[26px] font-bold text-gray-900">
                Kitchen Display
            </div>

            <div class="text-gray-500 text-sm">
                Realtime kitchen order monitoring
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-full font-medium transition-colors text-sm"
                    id="fullscreen-btn">
                <i class="bi bi-arrows-fullscreen"></i>
                Fullscreen
            </button>
            <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-bold">
                LIVE
            </span>
        </div>

    </div>

</div>

<div class="px-3 sm:px-4 py-4 sm:py-6">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5 mt-5">

        {{-- PENDING --}}
        <div class="bg-white rounded-3xl p-4 min-h-[70vh] shadow-lg shadow-gray-200/60">

            <div class="flex items-center justify-between mb-4">

                <div class="font-bold text-lg text-gray-900">
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
        <div class="bg-white rounded-3xl p-4 min-h-[70vh] shadow-lg shadow-gray-200/60">

            <div class="flex items-center justify-between mb-4">

                <div class="font-bold text-lg text-gray-900">
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
        <div class="bg-white rounded-3xl p-4 min-h-[70vh] shadow-lg shadow-gray-200/60">

            <div class="flex items-center justify-between mb-4">

                <div class="font-bold text-lg text-gray-900">
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
