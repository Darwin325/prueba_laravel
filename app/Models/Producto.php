<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function nombre(): Attribute
    {
        return new Attribute(
            get: fn($valor) => ucfirst($valor),
            set: fn($valor) => strtolower($valor)
        );
    }
}
