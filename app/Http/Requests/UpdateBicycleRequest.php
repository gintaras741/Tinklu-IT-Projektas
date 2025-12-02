<?php

namespace App\Http\Requests;

use App\Models\BicyclePart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateBicycleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'components' => ['required', 'array', 'min:1'],
            'components.*.bicycle_part_id' => ['required', 'exists:bicycle_parts,id'],
            'components.*.quantity' => ['required', 'integer', 'in:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for your bicycle.',
            'components.required' => 'Please select at least one component for your bicycle.',
            'components.min' => 'Your bicycle must have at least one component.',
        ];
    }
}

