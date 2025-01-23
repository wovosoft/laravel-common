<?php

namespace Wovosoft\LaravelCommon\Traits;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

//https://github.com/SocolaDaiCa/laravel-table-prefix/blob/master/src/HasTablePrefix.php
trait HasTablePrefix
{
    protected string $tableWithPrefix;

    /**
     * Get the prefix associated with the model.
     * Override this method, in case table prefix coming from config file.
     * When prefix is static file, just use $prefix = "prefix"
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix ?? '';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        if (isset($this->tableWithPrefix)) {
            return $this->tableWithPrefix;
        }

        if ($this instanceof Pivot && !isset($this->table)) {
            $this->setTable($this->getPrefix() . str_replace(
                    '\\',
                    '',
                    Str::snake(Str::singular(class_basename($this)))
                ));

            return $this->tableWithPrefix;
        }

        return $this->getPrefix() . Str::snake(Str::pluralStudly(class_basename($this)));
    }

    /**
     * Set the table associated with the model.
     *
     * @param string $table
     * @return $this
     */
    public function setTable($table): static
    {
        $this->tableWithPrefix = $table;

        return $this;
    }

    public static function getTableName(): string
    {
        return with(new static())->getTable();
    }
}
