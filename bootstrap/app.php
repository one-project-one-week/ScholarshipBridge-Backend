<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([\App\Http\Middleware\GuestUser::class, HandleCors::class]);
        $middleware->alias(['admin' => AdminMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Your request could not found',
                    'errors' => $e->getMessage(),
                    'data' => null,

                ], 404);
            }
        });
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Db query Error',
                    'errors' => $e->getMessage(),
                    'data' => null,
                ], 500);
            }
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Wrong Request Method ',
                    'errors' => $e->getMessage(),
                    'data' => null,
                ], 405);
            }
        });


        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation Fails',
                    'errors' => $e->errors(),
                    'data' => null,
                ], 400);
            }
        });
        $exceptions->render(function (ErrorException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Server Error',
                    'errors' => $e->getMessage(),
                    'data' => null,
                ], 500);
            }
        });
    })->create();
