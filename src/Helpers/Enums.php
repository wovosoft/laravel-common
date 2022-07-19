<?php

namespace Wovosoft\LaravelCommon\Helpers;

enum Enums: string
{
    case Online_Service = "online";
    case ATM_DEBIT_Service = "atm_debit";
    case SMS_Service = "sms";
    case Others = "others";

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
