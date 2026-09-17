<?php

namespace App\Http\Requests\Sale;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.article_id' => ['required', 'integer', 'exists:articles,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'La venta debe tener al menos un artículo.',
            'items.min' => 'La venta debe tener al menos un artículo.',
            'items.*.article_id.required' => 'Selecciona un artículo válido.',
            'items.*.article_id.exists' => 'Uno de los artículos seleccionados no existe.',
            'items.*.quantity.required' => 'Indica la cantidad para cada artículo.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.article_id' => 'artículo',
            'items.*.quantity' => 'cantidad', //pongo titulos a la medida
        ];
    }
}
