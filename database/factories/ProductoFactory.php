<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $productos = [
            'teclado', 'mouse', 'audifono', 'monitor', 'cable HDMI', 'cable de poder',
            'luz led', 'teclado con luces', 'calculadora', 'celular', 'cargador celular',
            'cargador computador', 'forro celular', 'forro computador'
        ];
        return [
            'nombre' => collect($productos)->random(),
            'descripcion' => $this->faker->paragraph,
            'foto' => $this->faker->url,
            'precio' => $this->faker->randomNumber(5),
            'cantidad' => $this->faker->numberBetween(1, 50),
            'iva' => collect([15,19,21])->random(),
            //Solo son vendedores los primeros dos usuarios
            'vendedor_id' => collect([1,2])->random()
        ];
    }
}
