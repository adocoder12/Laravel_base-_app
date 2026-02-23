<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia; // Importado correctamente
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia // <-- ¡IMPORTANTE: Añade esto!
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, InteractsWithMedia;

    /**
     * Configuración de MediaLibrary para el Avatar
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile(); // Esto asegura que el usuario solo tenga UNA foto de perfil a la vez
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->width(150)
            ->height(150)
            ->sharpen(10);
    }

    /**
     * Atributos asignables (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active', // Añádelo si lo pusiste en tu migración
    ];

    /**
     * Atributos ocultos en las respuestas JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
        'media', // Ocultamos la relación cruda de media para usar tu Resource
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // Convierte el 1/0 de la DB en true/false
        ];
    }
}
