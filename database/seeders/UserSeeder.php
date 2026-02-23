<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\RolesEnum;
use App\Enums\PermissionsEnum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear todos los permisos definidos en el Enum
        foreach (PermissionsEnum::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        // 2. Crear Roles básicos (¡Asegúrate de que Manager esté aquí!)
        $admin   = Role::firstOrCreate(['name' => RolesEnum::Admin->value]);
        $manager = Role::firstOrCreate(['name' => RolesEnum::Manager->value]);
        $staff   = Role::firstOrCreate(['name' => RolesEnum::Staff->value]);
        $user    = Role::firstOrCreate(['name' => RolesEnum::User->value]);

        // 3. Asignar Permisos a Roles
        // Admin: Sincroniza todos los permisos existentes
        $admin->syncPermissions(Permission::all());

        // Manager: Permisos específicos
        $manager->syncPermissions([
            PermissionsEnum::ViewDashboard->value,
            PermissionsEnum::ManageProduct->value,
        ]);

        // 4. Crear Usuarios de prueba usando el helper
        $this->createUser('Admin Boss', 'admin@example.com', RolesEnum::Admin);
        $this->createUser('Manager One', 'manager@example.com', RolesEnum::Manager);
        $this->createUser('Staff Member', 'staff@example.com', RolesEnum::Staff);
    }

    private function createUser(string $name, string $email, RolesEnum $roleEnum): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $user->syncRoles([$roleEnum->value]);
    }
}
