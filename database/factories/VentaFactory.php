<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // Todos son clientes excepto los usuarios 1 y 2 que son vendedores
            'cliente_id' => User::all()->except([1,2])->random(),
            'numero_venta' => 0
        ];
    }
}
