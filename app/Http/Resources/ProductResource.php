<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => strtoupper($this->name), // Ejemplo: nombres en mayúsculas
            'price_label' => '$' . number_format($this->price, 2),
            'stock'       => $this->stock,
            'status'      => $this->stock > 0 ? 'En Stock' : 'Agotado',
            'created_at'  => $this->created_at->format('d/m/Y'),
        ];
    }
}
