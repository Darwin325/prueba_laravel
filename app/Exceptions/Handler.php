<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e) {
            if (request()->is('api/*')){
                // No se encontró la url
                if ($e instanceof NotFoundHttpException) {
                    return $this->errorResponse('No se encontró la URL especificada', 404);
                }

                // Métod de petición http no válido
                if ($e instanceof MethodNotAllowedHttpException) {
                    return $this->errorResponse('El método especificado en la petición no es válido', 405);
                }

                if ($e instanceof ModelNotFoundException) {
                    $model = strtolower(class_basename($e->getModel()));
                    return $this->errorResponse("No existe ninguna instancia de {$model} con el id especificado", 404);
                }

                return $this->errorResponse('Falla inesperada. Intente luego', 500);
            }
        });
    }
}
