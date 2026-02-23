<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * Respuesta de éxito estandarizada.
     */
    protected function successResponse($data, string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'status'  => 'Success',
            'data'    => $data
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * Respuesta de error estandarizada.
     */
    protected function errorResponse(string $message, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => 'Error',
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}
