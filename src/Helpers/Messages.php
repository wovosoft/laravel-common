<?php

namespace Wovosoft\LaravelCommon\Helpers;

use Illuminate\Http\JsonResponse;

class Messages
{
    public static function success(array $merge = []): JsonResponse
    {
        return response()->json(array_merge([
            "variant"=>"primary",
            "message" => "Successfully Done"
        ], $merge));
    }

    public static function failed(\Throwable $throwable, array $merge = []): JsonResponse
    {
        if (app()->environment("development")) {
            return response()->json(array_merge([
                "variant"=>"danger",
                "message" => $throwable->getMessage(),
                "file" => $throwable->getFile(),
                "line" => $throwable->getLine()
            ], $merge), 403);
        }
        return response()->json(array_merge([
            "message" => "Operation Failed",
        ], $merge), 403);
    }
}
