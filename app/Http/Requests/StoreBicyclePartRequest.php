<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PartType;
use Illuminate\Validation\Rule;

class StoreBicyclePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Middleware already checks role; allow.
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(PartType::values())],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
