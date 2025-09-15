<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null; // Ensure user authenticated
    }

    public function rules(): array
    {
        return [
            'investment_package_id' => 'required|exists:investment_packages,id',
            'amount' => 'required|numeric|min:0.01',
            'referral_code' => 'nullable|string|max:64'
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('referral_code') && $this->referral_code !== null) {
            $this->merge([
                'referral_code' => strtoupper(trim($this->referral_code))
            ]);
        }
    }
}
