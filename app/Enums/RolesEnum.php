<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Admin   = 'admin';
    case Manager = 'manager'; // Añadido para coincidir con tu Seeder
    case Staff   = 'staff';
    case User    = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin   => 'Administrador del Sistema',
            self::Manager => 'Gestor de Propiedades',
            self::Staff   => 'Personal de Soporte',
            self::User    => 'Cliente / Usuario Final',
        };
    }
}
