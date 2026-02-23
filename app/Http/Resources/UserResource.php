<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'is_active'=> $this->is_active,
            //avatar
            'avatar'       => $this->getFirstMediaUrl('avatars')
                ?: "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=random",

            // Si quieres enviar la miniatura de 150x150 que configuramos en el modelo:
            'avatar_preview' => $this->getFirstMediaUrl('avatars', 'preview') ?: null,
            //security & roles
            'roles' => $this->getRoleNames(),// Enviamos los nombres de los roles (ej: ['admin'])
            'permissions' => $this->getAllPermissions()->pluck('name'), // Enviamos los permisos (solo los nombres)
            'member_since' => $this->created_at->format('Y-m-d'),
        ];
    }
}
