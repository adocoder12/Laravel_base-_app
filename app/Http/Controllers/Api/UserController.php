<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


class UserController extends Controller
{
    /**
     * Admin: Listar todos los usuarios del sistema.
     */
    public function index(): JsonResponse
    {
        $users = QueryBuilder::for(User::class)
            ->with(['roles', 'permissions']) // Optimization: No more N+1 issues
            ->allowedFilters([
                'name',
                'is_active', // Changed 'status' to 'is_active' to match your DB
                AllowedFilter::exact('id'),
                AllowedFilter::scope('email'),
            ])
            ->allowedSorts(['name', 'email', 'is_active'])
            ->defaultSort('-created_at')
            ->paginate(10);
        $message = $users->isEmpty() ? 'No users found matching the criteria' : 'Users listed';
        return $this->successResponse(
            UserResource::collection($users)->response()->getData(true),
            $message
        );
    }
    /**
     * Admin: Ver detalle de un usuario específico.
     */
    public function show(User $user): JsonResponse
    {
        // Cargamos relaciones para que el Resource las incluya
        $user->load(['roles', 'permissions']);

        return $this->successResponse(
            new UserResource($user),
            'User profile'
        );
    }

    /**
     * Admin: Actualizar datos, roles y permisos de un usuario.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            // Validamos que el email sea único, excepto para este mismo usuario
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            // Los roles y permisos deben enviarse como un array de strings (ej: ["admin", "manager"])
            'roles' => 'sometimes|array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        // 1. Actualizar datos básicos (si se enviaron)
        $user->update($request->only(['name', 'email']));

        // 2. Sincronizar Roles (Spatie borra los anteriores y asigna los nuevos)
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // 3. Sincronizar Permisos directos
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return $this->successResponse(
            new UserResource($user),
            'User updated'
        );
    }

    /**
     * Admin: Activar/Desactivar cuenta de usuario.
     */
    public function toggleStatus(User $user): JsonResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'active' : 'offline';
        return $this->successResponse(
            new UserResource($user),
            "The user status is: {$status}"
        );
    }
}
