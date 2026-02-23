<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Enums\RolesEnum;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Registro de usuarios con asignación de rol automática.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // 1. Validamos los datos (incluyendo la imagen opcional)
        $validated = $request->validated();
        // 2. Creamos el usuario
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_active' => true,
        ]);
        // 3. Si el usuario subió una imagen, la guardamos con Spatie
        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatars');
        }

        // 4. Asignamos el rol por defecto
        $user->assignRole(RolesEnum::User->value);

        // 5. Devolvemos la respuesta con el token
        return $this->successResponse([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user'  => new UserResource($user)
        ], 'User created', 201);
    }

    /**
     * Inicio de sesión con retorno de Roles y Permisos.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Wrong username or Password.', 401);
        }

        if (!$user->is_active) {
            return $this->errorResponse('Your account is not active yet', 403);
        }

        return $this->successResponse([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user'  => new UserResource($user),
        ], 'Logged in');
    }

    /**
     * Cerrar sesión (Revocar token).
     */
    public function logout(Request $request): JsonResponse
    {
        // Revocamos el token que se está usando actualmente
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out');
    }

    public function me(Request $request): JsonResponse
    {
        // $request->user() obtiene el usuario gracias al token de Sanctum
        // UserResource formatea los datos (oculta password, muestra roles, etc.)
        return $this->successResponse(
            new UserResource($request->user()),
            'Welcome to your profile!'
        );
    }
}
