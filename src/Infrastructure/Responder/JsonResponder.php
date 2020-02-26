<?php
declare(strict_types=1);

namespace App\Infrastructure\Responder;


use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonResponder
{
    public function error(array $errors = [], int $code = 400): JsonResponse
    {
        return new JsonResponse([
            'errors' => $errors,
        ], $code);
    }

    public function data(array $data = [], int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'data' => $data
        ], $code);
    }
}
