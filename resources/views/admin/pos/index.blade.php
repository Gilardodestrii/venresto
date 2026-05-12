@extends('layouts.admin')

@section('content')

<style>
:root{
    --primary:#0ea5e9;
    --bg:#f4f8fc;
    --card:#ffffff;
    --border:#e5e7eb;
}

body{
    background:var(--bg);
}

.pos-wrapper{
    display:grid;
    grid-template-columns: 1fr 420px;
    gap:24px;
}

.pos-card{
    background:rgba(255,255,255,.88);
    border:1px solid rgba(255,255,255,.5);
    backdrop-filter:blur(16px);
    border-radius:24px;
    box-shadow:
        0 10px 30px rgba(15,23,42,.05);
}

.search-box{
    position:relative;
}

.search-box i{
    position:absolute;
    left:16px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
}

.search-input{
    border:none;
    width:100%;
    height:54px;
    border-radius:18px;
    padding-left:46px;
    background:#fff;
}

.search-input:focus{
    outline:none;
}

.category-scroll{
    display:flex;
    gap:12px;
    overflow:auto;
    padding-bottom:6px;
}

.category-btn{
    border:none;
    background:#fff;
    border-radius:16px;
    padding:10px 18px;
    white-space:nowrap;
    font-weight:600;
    transition:.2s;
    border:1px solid #e2e8f0;
}

.category-btn.active,
.category-btn:hover{
    background:var(--primary);
    color:white;
}

.menu-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:18px;
}

.menu-item{
    overflow:hidden;
    transition:.2s;
}

.menu-item:hover{
    transform:translateY(-4px);
}

.menu-image{
    width:100%;
    height:170px;
    object-fit:cover;
}

.menu-content{
    padding:16px;
}

.menu-title{
    font-weight:700;
    font-size:16px;
}

.menu-price{
    font-weight:700;
    color:var(--primary);
}

.btn-add{
    width:44px;
    height:44px;
    border:none;
    border-radius:14px;
    background:var(--primary);
    color:white;
    font-size:22px;
}

.cart-item{
    padding:14px;
    border-radius:18px;
    background:#f8fafc;
    margin-bottom:14px;
}

.qty-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.qty-btn{
    width:28px;
    height:28px;
    border:none;
    border-radius:8px;
    background:white;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
}

.summary-total{
    font-size:22px;
    font-weight:800;
}

.checkout-btn{
    height:56px;
    border:none;
    border-radius:18px;
    font-weight:700;
}

.btn-hold{
    background:#f1f5f9;
}

.btn-pay{
    background:var(--primary);
    color:white;
}

.alert{
    backdrop-filter:blur(14px);
    background:rgba(255,255,255,.9);
}

@media(max-width:991px){

    .pos-wrapper{
        grid-template-columns:1fr;
    }

}
</style>

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">POS Cashier</h3>

        <div class="text-secondary">
            Premium modern cashier system
        </div>
    </div>

</div>

{{-- ALERT SUCCESS --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">

        <div
            class="me-3 d-flex align-items-center justify-content-center"
            style="
                width:48px;
                height:48px;
                border-radius:16px;
                background:rgba(34,197,94,.15);
                color:#22c55e;
                font-size:22px;
            "
        >
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <div>
            <div class="fw-bold">
                Berhasil
            </div>

            <div>
                {{ session('success') }}
            </div>
        </div>

    </div>

@endif

{{-- ALERT ERROR --}}
@if(session('error'))

    <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">

        <div
            class="me-3 d-flex align-items-center justify-content-center"
            style="
                width:48px;
                height:48px;
                border-radius:16px;
                background:rgba(239,68,68,.15);
                color:#ef4444;
                font-size:22px;
            "
        >
            <i class="bi bi-x-circle-fill"></i>
        </div>

        <div>
            <div class="fw-bold">
                Error
            </div>

            <div>
                {{ session('error') }}
            </div>
        </div>

    </div>

@endif

{{-- VALIDATION ERROR --}}
@if ($errors->any())

    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">

        <div class="fw-bold mb-2">
            Validasi gagal
        </div>

        <ul class="mb-0 ps-3">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

    <form
        action="{{ route('tenant.admin.pos.store',  $currentTenant->slug) }}"
        method="POST"
        id="posForm"
    >
        @csrf

        <div class="pos-wrapper">

            {{-- LEFT --}}
            <div>

                <div class="pos-card p-4 mb-4">

                    <div class="search-box mb-4">
                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            class="search-input"
                            id="searchMenu"
                            placeholder="Cari menu..."
                        >
                    </div>

                    <div class="category-scroll">

                        <button
                            type="button"
                            class="category-btn active"
                            data-category="all"
                        >
                            Semua
                        </button>

                        @foreach($categories as $category)

                            <button
                                type="button"
                                class="category-btn"
                                data-category="{{ $category->id }}"
                            >
                                {{ $category->name }}
                            </button>

                        @endforeach

                    </div>

                </div>

                <div class="menu-grid">

                    @foreach($menuItems as $item)

                        <div
                            class="pos-card menu-item menu-filter"
                            data-name="{{ strtolower($item->name) }}"
                            data-category="{{ $item->category_id }}"
                        >

                            <img
                                src="{{ $item->image_url ?: 'https://placehold.co/600x400/png' }}"
                                class="menu-image"
                            >

                            <div class="menu-content">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <div class="menu-title">
                                            {{ $item->name }}
                                        </div>

                                        <div class="small text-secondary mb-2">
                                            {{ $item->category?->name }}
                                        </div>

                                        <div class="menu-price">
                                            Rp {{ number_format($item->price,0,',','.') }}
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn-add addToCart"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-price="{{ $item->price }}"
                                    >
                                        +
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- RIGHT --}}
            <div>

                <div class="pos-card p-4 sticky-top" style="top:90px;">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Pilih Meja
                        </label>

                        <select
                            name="table_code"
                            class="form-select form-select-lg"
                            required
                        >

                            <option value="">
                                -- Pilih Meja --
                            </option>

                            @foreach($tables as $table)

                                <option value="{{ $table->table_code }}">
                                    {{ $table->table_code }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- nama customer   --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Customer
                        </label>

                        <input
                            type="text"
                            name="customer_name"
                            class="form-control form-control-lg"
                            required
                        >
                    </div>

                    {{-- customer phone --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            No HP
                        </label>

                        <input
                            type="text"
                            name="customer_phone"
                            class="form-control form-control-lg"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Service
                        </label>

                        <input
                            type="number"
                            name="service"
                            id="serviceInput"
                            class="form-control form-control-lg"
                            value="0"
                        >
                    </div>

                    <hr>

                    <div
                        id="cartContainer"
                        style="max-height:350px;overflow:auto;"
                    >

                    </div>

                    <hr>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong id="subtotalText">Rp 0</strong>
                    </div>

                    <div class="summary-row">
                        <span>Discount</span>

                        <input
                            type="number"
                            class="form-control w-50"
                            name="discount"
                            id="discountInput"
                            value="0"
                        >
                    </div>

                    <div class="summary-row">
                        <span>Tax</span>

                        <input
                            type="number"
                            class="form-control w-50"
                            name="tax"
                            id="taxInput"
                            value="0"
                        >
                    </div>

                    <div class="summary-row">
                        <span>Service</span>
                        <strong id="serviceText">Rp 0</strong>
                    </div>

                    <hr>

                    <div class="summary-row summary-total">
                        <span>Grand Total</span>
                        <span id="grandTotalText">Rp 0</span>
                    </div>

                    {{-- customer note --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Customer Note
                        </label>

                        <textarea
                            name="customer_note"
                            class="form-control form-control-lg"
                        ></textarea>
                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select form-select-lg"
                        >
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                            <option value="debit">Debit</option>
                        </select>

                    </div>

                    <div class="d-grid gap-3">

                        <button
                            type="submit"
                            name="action"
                            value="hold"
                            class="checkout-btn btn-hold"
                        >
                            Simpan / Hold
                        </button>

                        <button
                            type="submit"
                            name="action"
                            value="paid"
                            class="checkout-btn btn-pay"
                        >
                            Bayar
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

let cart = [];

function formatRupiah(number)
{
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

function renderCart()
{
    let html = '';
    let subtotal = 0;

    cart.forEach((item,index)=>{

        subtotal += item.qty * item.price;

        html += `
            <div class="cart-item">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="fw-bold">
                            ${item.name}
                        </div>

                        <small class="text-secondary">
                            Rp ${Number(item.price).toLocaleString('id-ID')}
                        </small>
                    </div>

                    <div class="qty-box">

                        <button
                            type="button"
                            class="qty-btn"
                            onclick="decreaseQty(${index})"
                        >-</button>

                        <strong>${item.qty}</strong>

                        <button
                            type="button"
                            class="qty-btn"
                            onclick="increaseQty(${index})"
                        >+</button>

                    </div>

                </div>

                <textarea
                    class="form-control mt-3"
                    placeholder="Catatan item..."
                    oninput="updateNote(${index}, this.value)"
                >${item.note ?? ''}</textarea>

                <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                <input type="hidden" name="items[${index}][price]" value="${item.price}">
                <input
                    type="hidden"
                    name="items[${index}][note]"
                    value="${item.note ? item.note : ''}"
                >

            </div>
        `;
    });

    document.getElementById('cartContainer').innerHTML = html;

    let discount = parseFloat(document.getElementById('discountInput').value || 0);
    let tax = parseFloat(document.getElementById('taxInput').value || 0);
    let service = parseFloat(document.getElementById('serviceInput').value || 0);

    let grandTotal = (subtotal - discount) + tax + service;

    document.getElementById('subtotalText').innerHTML = formatRupiah(subtotal);
    document.getElementById('serviceText').innerHTML = formatRupiah(service);
    document.getElementById('grandTotalText').innerHTML = formatRupiah(grandTotal);
}

function increaseQty(index)
{
    cart[index].qty++;
    renderCart();
}

function decreaseQty(index)
{
    if(cart[index].qty > 1){
        cart[index].qty--;
    }else{
        cart.splice(index,1);
    }

    renderCart();
}

function updateNote(index, value)
{
    cart[index].note = value;
    renderCart(); // penting supaya hidden input ikut update
}

document.querySelectorAll('.addToCart').forEach(btn=>{

    btn.addEventListener('click', function(){

        let id = this.dataset.id;
        let name = this.dataset.name;
        let price = this.dataset.price;


        let existing = cart.find(item => item.id == id);

        if(existing){
            existing.qty++;
        }else{
            cart.push({
                id:id,
                name:name,
                price:price,
                qty:1,
                note:''
            });
        }

        renderCart();

    });

});

document.getElementById('discountInput')
    .addEventListener('input', renderCart);

document.getElementById('taxInput')
    .addEventListener('input', renderCart);

document.getElementById('serviceInput')
    .addEventListener('input', renderCart);

document.getElementById('searchMenu')
.addEventListener('keyup', function(){

    let keyword = this.value.toLowerCase();

    document.querySelectorAll('.menu-filter')
    .forEach(item=>{

        let name = item.dataset.name;

        item.style.display = name.includes(keyword)
            ? 'block'
            : 'none';

    });

});

document.querySelectorAll('.category-btn')
.forEach(btn=>{

    btn.addEventListener('click', function(){

        document.querySelectorAll('.category-btn')
            .forEach(x=>x.classList.remove('active'));

        this.classList.add('active');

        let category = this.dataset.category;

        document.querySelectorAll('.menu-filter')
        .forEach(item=>{

            if(category == 'all'){
                item.style.display = 'block';
            }else{

                item.style.display =
                    item.dataset.category == category
                    ? 'block'
                    : 'none';
            }

        });

    });

});

</script>

@endsection