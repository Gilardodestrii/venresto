@extends('layouts.app')

@section('content')

<style>
    body {
        background: #f8fbff;
    }

    .pos-header {
        position: sticky;
        top: 0;
        background: white;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        z-index: 10;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 12px;
        padding-bottom: 140px;
    }

    .menu-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .menu-img {
        width: 100%;
        height: 110px;
        object-fit: cover;
    }

    .menu-body {
        padding: 10px;
    }

    .menu-title {
        font-size: 14px;
        font-weight: 600;
    }

    .menu-price {
        font-size: 13px;
        color: #0ea5e9;
        font-weight: 600;
    }

    .btn-add {
        width: 100%;
        margin-top: 8px;
        border-radius: 12px;
        font-size: 12px;
    }

    /* BOTTOM NAV */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        z-index: 20;
    }

    .nav-item {
        font-size: 12px;
        text-align: center;
        color: #6b7280;
    }

    .nav-item.active {
        color: #0ea5e9;
        font-weight: 600;
    }

    /* CART */
    .cart-drawer {
        position: fixed;
        bottom: 60px;
        left: 0;
        right: 0;
        background: white;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        padding: 15px;
        max-height: 55vh;
        overflow-y: auto;
        display: none;
        z-index: 30;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed #eee;
        padding: 10px 0;
        font-size: 13px;
    }

    .cart-badge {
        position: absolute;
        top: 0;
        right: 20%;
        background: red;
        color: white;
        font-size: 10px;
        border-radius: 20px;
        padding: 2px 6px;
    }
</style>

{{-- HEADER --}}
<div class="pos-header">
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="mb-0">🍽️ Meja {{ $table }}</h6>
            <small class="text-muted">POS Order</small>
        </div>
        <div style="position:relative;">
            🛒
            <span class="cart-badge" id="cartCount">0</span>
        </div>
    </div>
</div>

{{-- MENU --}}
<div class="menu-grid">
    @foreach($menus as $menu)
    <div class="menu-card">
        <img src="{{ $menu->image_url }}" class="menu-img">

        <div class="menu-body">
            <div class="menu-title">{{ $menu->name }}</div>
            <div class="menu-price">Rp {{ number_format($menu->price) }}</div>

            <button class="btn btn-primary btn-sm btn-add"
                onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                + Pesan
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- CART --}}
<div class="cart-drawer" id="cartDrawer">
    <h6>🛒 Cart</h6>

    <div id="cart"></div>

    <hr>

    <label>Discount (Rp)</label>
    <input type="number" class="form-control mb-2"
        onchange="setOrderField('discount', this.value)">

    <label>Tax (%)</label>
    <input type="number" class="form-control mb-2"
        onchange="setOrderField('tax', this.value)">

    <label>Service (%)</label>
    <input type="number" class="form-control mb-2"
        onchange="setOrderField('service', this.value)">

    <label>Payment Method</label>
    <select class="form-control mb-2"
        onchange="setOrderField('payment_method', this.value)">
        <option value="cash">Cash</option>
        <option value="qris">QRIS</option>
        <option value="card">Card</option>
    </select>

    <label>Customer Note</label>
    <textarea class="form-control mb-2"
        onchange="setOrderField('customer_note', this.value)"></textarea>

    <div id="cartSummary" class="mt-2"></div>

    <button class="btn btn-success w-100 mt-3" onclick="checkout()">
        Checkout
    </button>
</div>

{{-- BOTTOM NAV --}}
<div class="bottom-nav">
    <div class="nav-item" onclick="toggleMenu()">🍽️ Menu</div>
    <div class="nav-item" onclick="toggleCart()">🛒 Cart</div>
    <div class="nav-item" onclick="checkout()">💳 Checkout</div>
</div>

<script>

let cart = [];

let order = {
    discount: 0,
    tax: 0,
    service: 0,
    payment_method: 'cash',
    customer_note: ''
};

/* =========================
   ADD CART (NO DUPLICATE)
========================= */
function addToCart(id, name, price){

    let item = cart.find(x => x.id === id);

    if(item){
        item.qty += 1;
    } else {
        cart.push({
            id,
            name,
            price,
            qty: 1,
            note: ''
        });
    }

    render();

    document.getElementById('cartDrawer').style.display = 'block';
}

/* =========================
   QTY CONTROL
========================= */
function changeQty(id, type){

    let item = cart.find(x => x.id === id);
    if(!item) return;

    if(type === 'plus') item.qty++;
    if(type === 'minus') item.qty--;

    if(item.qty <= 0){
        cart = cart.filter(x => x.id !== id);
    }

    render();
}

/* =========================
   ITEM NOTE
========================= */
function updateItemNote(id, value){
    let item = cart.find(x => x.id === id);
    if(item){
        item.note = value;
    }
}

/* =========================
   ORDER FIELD
========================= */
function setOrderField(field, value){
    order[field] = value || 0;
    render();
}

/* =========================
   CALCULATE TOTAL
========================= */
function calculate(){

    let subtotal = cart.reduce((a,b)=> a + (b.price * b.qty), 0);

    let discount = parseFloat(order.discount) || 0;
    let taxRate = parseFloat(order.tax) || 0;
    let serviceRate = parseFloat(order.service) || 0;

    let tax = subtotal * taxRate / 100;
    let service = subtotal * serviceRate / 100;

    let grand_total = subtotal - discount + tax + service;

    return { subtotal, discount, tax, service, grand_total };
}

/* =========================
   RENDER UI
========================= */
function render(){

    let html = '';
    let totalQty = 0;

    cart.forEach(c => {

        totalQty += c.qty;

        html += `
        <div class="cart-item">

            <div style="width:70%">
                <b>${c.name}</b><br>
                <small>${c.qty} x Rp ${c.price.toLocaleString()}</small>

                <div class="d-flex mt-1">
                    <button class="btn btn-sm btn-light"
                        onclick="changeQty(${c.id}, 'minus')">-</button>

                    <span class="mx-2">${c.qty}</span>

                    <button class="btn btn-sm btn-light"
                        onclick="changeQty(${c.id}, 'plus')">+</button>
                </div>

                <input type="text"
                    class="form-control form-control-sm mt-2"
                    placeholder="Catatan..."
                    value="${c.note || ''}"
                    oninput="updateItemNote(${c.id}, this.value)">
            </div>

            <div>
                <b>Rp ${(c.qty * c.price).toLocaleString()}</b>
            </div>

        </div>
        `;
    });

    let calc = calculate();

    document.getElementById('cart').innerHTML = html;
    document.getElementById('cartCount').innerText = totalQty;

    document.getElementById('cartSummary').innerHTML = `
        <div class="border-top pt-2">
            <div>Subtotal: Rp ${calc.subtotal.toLocaleString()}</div>
            <div>Discount: Rp ${calc.discount.toLocaleString()}</div>
            <div>Tax: Rp ${calc.tax.toLocaleString()}</div>
            <div>Service: Rp ${calc.service.toLocaleString()}</div>
            <h6>Grand Total: Rp ${calc.grand_total.toLocaleString()}</h6>
        </div>
    `;
}

/* =========================
   TOGGLE CART
========================= */
function toggleCart(){
    let el = document.getElementById('cartDrawer');
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}

function toggleMenu(){
    document.getElementById('cartDrawer').style.display = 'none';
}

/* =========================
   CHECKOUT
========================= */
function checkout(){

    if(cart.length === 0){
        alert('Cart kosong');
        return;
    }

    let calc = calculate();

    fetch(window.location.href.replace('/qr/{{ $table }}','/qr/order'), {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            table: '{{ $table }}',
            items: cart,
            subtotal: calc.subtotal,
            discount: calc.discount,
            tax: calc.tax,
            service: calc.service,
            grand_total: calc.grand_total,
            payment_method: order.payment_method,
            customer_note: order.customer_note
        })
    })
    .then(r => r.json())
    .then(res => {
        alert(res.message);
        cart = [];
        render();
        toggleCart();
    });
}

</script>

@endsection