<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function handle()
    {
        // Kirim email struk ke pelanggan
        Mail::send('emails.receipt', ['order' => $this->order], function ($mail) {
            $mail->to($this->order->customer_email)
                 ->subject('Struk Pesanan #' . $this->order->code);
        });
    }
}