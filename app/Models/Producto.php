<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory, SoftDeletes;
    const IVA = [15,19,21];

    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class)
            ->withTimestamps()->withPivot('cantidad');
    }

    public function nombre(): Attribute
    {
        return new Attribute(
            get: fn($valor) => ucfirst($valor),
            set: fn($valor) => strtolower($valor)
        );
    }

    public function foto(): Attribute
    {
        return new Attribute(
            get: fn($valor) => asset(Storage::url($valor)),
        );
    }

    public function descripcion(): Attribute
    {
        return new Attribute(
            get: fn($valor) => ucfirst($valor),
            set: fn($valor) => strtolower($valor)
        );
    }

    // Aplicar el iva a cada uno de los precios
    public function precio_con_iva()
    {
        return round(($this->precio * (($this->iva / 100) + 1)), 2);
    }

    // Obtener el valor total de todos los productos del mismo tipo vendidos
    public function valor_total()
    {
        return round($this->precio_con_iva() * $this->vendido->cantidad, 2);
    }
}
