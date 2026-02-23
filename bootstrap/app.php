<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\Permission\Exceptions\UnauthorizedException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Habilita el manejo de estado para APIs (Sanctum)
        $middleware->statefulApi();

        // --- REGISTRO DE ALIAS DE MIDDLEWARE ---
        // Esto soluciona el error "Target class [role] does not exist"
        $middleware->alias([
            'role'               => RoleMiddleware::class,
            'permission'         => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // EVITA EL ERROR "Route [login] not defined"
        // Si el usuario no está autenticado, no lo redirigimos a ninguna parte.
        $middleware->redirectGuestsTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // --- EL ESCUDO DE FORMATO ---
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        // 1. Error 401: No autenticado
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'No autenticado. Token faltante, inválido o expirado.',
                'errors'  => null
            ], 401);
        });

        // 2. Error 422: Fallo de Validación
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Los datos proporcionados no son válidos.',
                'errors'  => $e->errors(),
            ], 422);
        });

        // 3. Error 404: Recurso o Ruta no encontrada
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Check if the 404 was caused by a missing User model
            $message = 'El recurso solicitado no existe.';

            if ($e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $model = $e->getPrevious()->getModel();
                if ($model === \App\Models\User::class) {
                    $message = 'User not found';
                }

                if ($model === \App\Models\Product::class) {
                    $message = 'Product not found';
                }
            }

            return response()->json([
                'status'  => 'Error',
                'message' => $message,
                'errors'  => null
            ], 404);
        });

        // 4. Error 403: Acceso denegado (Middleware de Laravel o Spatie)
        // Agregamos UnauthorizedException de Spatie para mayor precisión
        $exceptions->render(function (AccessDeniedHttpException|UnauthorizedException $e, Request $request) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Acceso denegado. No tienes los roles o permisos necesarios.',
                'errors'  => null
            ], 403);
        });

        // 5. Error 500: Error interno del servidor
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : 'Ocurrió un error interno en el servidor.',
                    'errors'  => null
                ], 500);
            }
        });

    })->create();
