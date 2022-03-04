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
}
