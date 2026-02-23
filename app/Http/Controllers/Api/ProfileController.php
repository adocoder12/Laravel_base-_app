<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Usuario: Cambiar su propia contraseña.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // Exige la contraseña actual y verifica que coincida en la DB
            'current_password' => 'required|current_password',
            // Exige nueva contraseña, min 8 chars, y que se envíe 'new_password_confirmation'
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Error : trying to change password', 422, $validator->errors());
        }

        // Actualizamos la contraseña del usuario que hizo la petición
        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return $this->successResponse(null, 'Password Updated');
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $user = $request->user();

        // Spatie maneja la carga y reemplazo automático
        $user->addMediaFromRequest('avatar')
            ->toMediaCollection('avatars');

        return $this->successResponse(
            new UserResource($user),
            'Avatar Changed!'
        );
    }
}
