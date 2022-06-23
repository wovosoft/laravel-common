<?php

namespace Wovosoft\LaravelCommon\Helpers;

use Illuminate\Support\Facades\Route;

class Routes
{
    public static function register(string $controller, string $name): void
    {
        Route::controller($controller)
            ->prefix(str($name)->plural()->lower())
            ->name(str($name)->plural()->lower() . ".")
            ->group(function () use ($name) {
                $name = str($name)->singular()->lower();

                Route::put("/store", "store")->name("store");
                Route::put("/update/{" . $name . "}", "update")->name("update");
                Route::delete("/destroy/{" . $name . "}", "destroy")->name("destroy");
                Route::post("/find/{" . $name . "}", "find")->name("find");
                Route::post("/", "index")->name("index");
                Route::post("/options", "options")->name("options");
            });

    }
}
