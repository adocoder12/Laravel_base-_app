<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- No olvides importar Str

class Product extends Model
{
    use HasFactory;

    // AÑADIR 'slug' AQUÍ 👇
    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'stock',
        'is_active',
    ];

    // Opcional pero recomendado: Autogenerar el slug si alguien olvida enviarlo
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
