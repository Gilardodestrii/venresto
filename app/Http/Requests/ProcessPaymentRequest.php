<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'required|string|max:50',
            'paid_amount' => 'required|numeric|min:0',
            'cashier_session_id' => 'nullable|exists:cashier_sessions,id',
        ];
    }
}
