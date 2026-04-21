<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:car,motorcycle',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'color' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O tipo do veículo é obrigatório.',
            'type.in' => 'O tipo do veículo deve ser "car" ou "motorcycle".',
            'brand.required' => 'A marca do veículo é obrigatória.',
            'model.required' => 'O modelo do veículo é obrigatório.',
            'year.required' => 'O ano do veículo é obrigatório.',
            'year.integer' => 'O ano do veículo deve ser um número inteiro.',
            'year.min' => 'O ano do veículo deve ser no mínimo 1900.',
            'year.max' => 'O ano do veículo não pode ser maior que o ano atual.',
            'price.required' => 'O preço do veículo é obrigatório.',
            'price.numeric' => 'O preço do veículo deve ser um número.',
            'price.min' => 'O preço do veículo deve ser no mínimo 0.',
            'color.required' => 'A cor do veículo é obrigatória.',
            'mileage.required' => 'A quilometragem do veículo é obrigatória.',
            'mileage.integer' => 'A quilometragem do veículo deve ser um número inteiro.',
            'mileage.min' => 'A quilometragem do veículo deve ser no mínimo 0.',
        ];
    }
}
