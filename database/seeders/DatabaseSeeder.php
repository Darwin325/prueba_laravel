<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(7)->create();
        $this->call(ProductoSeeder::class);
        Venta::factory(15)->create()
            ->each(function ($venta) {
                $productos = Producto::all()->random(mt_rand(1,3));
                $productos->map(function ($producto) use($venta) {
                    $venta->productos()->attach($producto->id, [
                        'cantidad' => mt_rand(1, $producto->cantidad)
                    ]);
                });
                $venta->numero_venta = $venta->id;
                $venta->save();
            });
    }
}
