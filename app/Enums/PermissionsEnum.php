<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    // General
    case ViewDashboard = 'view dashboard';

    // User & Role Management
    case ManageUsers = 'manage users';
    case ManageRoles = 'manage roles';
    case ManagePermissions = 'manage permissions';

    // Product Management
    case ManageProduct = 'manage products';
    case ViewAssignedProduct = 'view assigned products';

    /**
     * Obtener todos los valores para el Seeder
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Agrupar permisos por categorías (Útil para la UI de gestión de roles)
     */
    public static function getByCategory(): array
    {
        return [
            'Users' => [
                self::ManageUsers,
                self::ManageRoles,
                self::ManagePermissions,
            ],
            'Products' => [
                self::ManageProduct,
                self::ViewAssignedProduct,
            ],
            'General' => [
                self::ViewDashboard,
            ],
        ];
    }
}
