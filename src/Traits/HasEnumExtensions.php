<?php

namespace Wovosoft\LaravelCommon\Traits;

trait HasEnumExtensions
{
    public static function __callStatic(string $name, array $arguments)
    {
        if (!method_exists(__CLASS__, $name)) {
            try {
                $options = static::flat();
                if (key_exists($name, $options)) {
                    return $options[$name]?->value;
                }
                return null;
            } catch (\Throwable $throwable) {
                return null;
            }
        }
        return null;
    }

    public static function toOptions(bool $asJson = false): array|bool|string
    {
        $records = array_map(fn($op) => [
            "text" => str($op->name)->title()->replace("_", " "),
            "value" => $op->value
        ], self::cases());

        if ($asJson) {
            return json_encode($records);
        }
        return $records;
    }

    public static function toArray(): array
    {
        return array_merge(...array_map(fn($op) => [$op->name => $op->value], self::cases()));
    }

    public static function toJson(): bool|string
    {
        return json_encode(static::toArray());
    }

    public static function values(): array
    {
        return array_map(fn($op) => $op->value, self::cases());
    }

    public static function keys(): array
    {
        return array_map(fn($op) => $op->name, self::cases());
    }

    private static function flat(): array
    {
        return array_merge(...array_map(fn($op) => [$op->name => $op], self::cases()));
    }
}
