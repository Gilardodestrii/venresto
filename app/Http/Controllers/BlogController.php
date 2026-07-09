<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = [
            [
                'title' => 'Cara Meningkatkan Penjualan Restoran dengan QR Code',
                'slug' => 'cara-meningkatkan-penjualan-restoran-dengan-qr-code',
                'excerpt' => 'Pelajari bagaimana QR code bisa meningkatkan penjualan restoran Anda dengan sistem pemesanan online yang efisien.',
                'date' => '2026-07-10',
            ],
            [
                'title' => '5 Alasan Mengapa Restoran Anda Butuh Sistem Pemesanan Online',
                'slug' => '5-alasan-mengapa-restoran-butuh-sistem-pemesanan-online',
                'excerpt' => 'Sistem pemesanan online bukan hanya tren, tapi kebutuhan untuk restoran modern. Ini alasannya!',
                'date' => '2026-07-05',
            ],
        ];

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $posts = [
            'cara-meningkatkan-penjualan-restoran-dengan-qr-code' => [
                'title' => 'Cara Meningkatkan Penjualan Restoran dengan QR Code',
                'content' => '<p>Di era digital ini, pelanggan restoran menginginkan kemudahan dan kecepatan. Salah satu cara untuk memenuhi ekspektasi tersebut adalah dengan menggunakan <strong>sistem pemesanan restoran online</strong> berbasis QR code. Berikut adalah beberapa cara QR code bisa meningkatkan penjualan restoran Anda:</p>
                    <ol>
                        <li><strong>Mengurangi Antrian:</strong> Pelanggan bisa langsung memesan dari meja tanpa menunggu pelayan.</li>
                        <li><strong>Meningkatkan Efisiensi:</strong> Pesanan langsung masuk ke dapur, mengurangi kesalahan komunikasi.</li>
                        <li><strong>Data Pelanggan:</strong> Kumpulkan data pelanggan untuk program loyalitas dan promosi.</li>
                        <li><strong>Upselling Mudah:</strong> Tampilkan rekomendasi menu atau promo khusus di halaman pemesanan.</li>
                        <li><strong>Biaya Operasional Rendah:</strong> Tidak perlu mencetak menu fisik atau menambah staf.</li>
                    </ol>
                    <p>Dengan <a href="{{ route(\'landing.features\') }}" class="text-blue-600 hover:underline"><strong>Venresto</strong></a>, Anda bisa menerapkan sistem ini dengan mudah dan cepat. Coba sekarang dan rasakan perbedaannya!</p>',
                'date' => '2026-07-10',
            ],
            '5-alasan-mengapa-restoran-butuh-sistem-pemesanan-online' => [
                'title' => '5 Alasan Mengapa Restoran Anda Butuh Sistem Pemesanan Online',
                'content' => '<p>Sistem pemesanan online bukan lagi pilihan, tapi kebutuhan untuk restoran modern. Berikut adalah 5 alasan mengapa restoran Anda membutuhkannya:</p>
                    <ol>
                        <li><strong>Meningkatkan Penjualan:</strong> Pelanggan lebih cenderung memesan lebih banyak saat prosesnya mudah.</li>
                        <li><strong>Mengurangi Kesalahan Pesanan:</strong> Pesanan langsung masuk ke sistem, mengurangi kesalahan manusia.</li>
                        <li><strong>Analisis Data:</strong> Dapatkan insight tentang menu favorit, waktu ramai, dan perilaku pelanggan.</li>
                        <li><strong>Pengalaman Pelanggan Lebih Baik:</strong> Pelanggan bisa memesan kapan saja, bahkan sebelum sampai di restoran.</li>
                        <li><strong>Kompetitif:</strong> Restoran lain sudah menggunakannya. Jangan sampai ketinggalan!</li>
                    </ol>
                    <p>Dengan <a href="{{ route(\'landing.features\') }}" class="text-blue-600 hover:underline"><strong>Venresto</strong></a>, Anda bisa mendapatkan semua manfaat ini tanpa ribet. Daftar sekarang dan dapatkan uji coba gratis!</p>',
                'date' => '2026-07-05',
            ],
        ];

        if (!array_key_exists($slug, $posts)) {
            abort(404);
        }

        return view('blog.show', ['post' => $posts[$slug]]);
    }
}