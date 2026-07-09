@extends('layouts.landing')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Sistem Pemesanan Restoran Online Terbaik</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Venresto membantu restoran Anda menerima pesanan online, mengelola meja, dan meningkatkan penjualan dengan sistem QR code yang mudah digunakan.
            </p>
            <a href="#cta" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Coba Gratis Sekarang
            </a>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Mengapa Memilih Venresto?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Pemesanan QR Code</h3>
                    <p class="text-gray-600">
                        Pelanggan bisa memesan langsung dari meja menggunakan <strong>QR code</strong>.
                        Tidak perlu menunggu pelayan, sehingga mengurangi antrian dan meningkatkan kepuasan pelanggan.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Manajemen Restoran Mudah</h3>
                    <p class="text-gray-600">
                        Kelola menu, meja, dan pesanan dari satu dashboard. <strong>Sistem pemesanan restoran online</strong>
                        ini dirancang untuk efisiensi dan kemudahan penggunaan.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Laporan Penjualan Real-Time</h3>
                    <p class="text-gray-600">
                        Dapatkan laporan penjualan harian, mingguan, dan bulanan secara real-time.
                        Optimalkan bisnis restoran Anda dengan data yang akurat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Cara Kerja Venresto</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">1. Scan QR Code</h3>
                    <p class="text-gray-600">Pelanggan scan QR code di meja untuk membuka menu.</p>
                </div>
                <div>
                    <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">2. Pesan Makanan</h3>
                    <p class="text-gray-600">Pilih menu dan tambahkan ke keranjang.</p>
                </div>
                <div>
                    <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">3. Bayar & Nikmati</h3>
                    <p class="text-gray-600">Bayar melalui sistem dan nikmati makanan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="cta" class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Siap Meningkatkan Penjualan Restoran Anda?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Daftar sekarang dan dapatkan uji coba gratis selama 14 hari. Tidak perlu kartu kredit!
            </p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Daftar Gratis
            </a>
        </div>
    </section>
@endsection