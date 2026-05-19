<?php

namespace App\Http\Requests;

use App\Models\Pemesanan;
use Illuminate\Foundation\Http\FormRequest;

class ProcessCashPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uang_diterima' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'uang_diterima.required' => 'Masukkan jumlah uang tunai yang diterima.',
            'uang_diterima.numeric'  => 'Jumlah uang harus berupa angka.',
            'uang_diterima.min'      => 'Jumlah uang tidak valid.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $pemesanan = $this->resolvePemesanan();
            if (!$pemesanan) {
                return;
            }

            $total = round((float) $pemesanan->total_harga, 2);
            $uang  = round((float) $this->input('uang_diterima'), 2);

            if ($uang < $total) {
                $kurang = $total - $uang;
                $validator->errors()->add(
                    'uang_diterima',
                    'Uang tunai tidak mencukupi. Kurang Rp ' . number_format($kurang, 0, ',', '.')
                );
            }
        });
    }

    protected function resolvePemesanan(): ?Pemesanan
    {
        $pemesanan = $this->route('pemesanan');

        return $pemesanan instanceof Pemesanan ? $pemesanan : null;
    }
}
