<?php

namespace Wovosoft\LaravelCommon\Traits;

trait HasEnumExtensions
{
    /**
     * Access values by name using magic methods
     */
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

    public static function toJson(int $flags = 0, int $depth = 512): bool|string
    {
        return json_encode(static::toArray(), $flags, $depth);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    private static function flat(): array
    {
        return array_merge(...array_map(fn($op) => [$op->name => $op], self::cases()));
    }
}
