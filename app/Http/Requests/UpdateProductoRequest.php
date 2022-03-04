<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' => 'string',
            'descripcion' => 'string',
            'foto' => 'image',
            'cantidad' => 'integer',
            'precio' => 'integer',
            'iva' => ['integer', Rule::in(Producto::IVA)],
            'vendedor_id' => 'exists:App\Models\Vendedor,id'
        ];
    }
}
