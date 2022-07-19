<?php

namespace Wovosoft\LaravelCommon\Helpers;

class Enums
{
    public static function options($enum): array
    {
        $out = [];
        foreach ($enum::cases() as $case) {
            $out[] = [
                "text" => str($case->name)->title()->replace("_", " ")->value(),
                "value" => $case->value ?: $case->name
            ];
        }
        return $out;
    }
}
