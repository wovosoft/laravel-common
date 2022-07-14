<?php

namespace Wovosoft\LaravelCommon\Helpers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Wovosoft\BdHrmProfile\Interfaces\HasRulesInterface;


class Data
{
    /**
     * can be used for both store/update
     * $model Provides instance of their respected Model class, which must have rules() static function.
     * So, the rules function can be used to retrieve data submission rules.
     * @throws \Throwable
     */
    public static function store(Model $model, Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            /* @var $model HasRulesInterface */
            $data = $request->validate($model->rules());
            $model->forceFill($data)->saveOrFail();
            DB::commit();
            return Messages::success();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return Messages::failed($exception);
        }
    }

    public static function paginate(Builder $builder, Request $request): LengthAwarePaginator
    {
        return $builder
            ->when($request->input("filter"), fn(Builder $builder, string $filter) => $builder->search($filter))
            ->paginate(
                perPage: $request->input("per_page") ?: 15,
                page: $request->input("current_page") ?: $request->input("page")?: 1
            );
    }

    /**
     * @throws \Throwable
     */
    public static function destroy(Model $model): JsonResponse
    {
        DB::beginTransaction();
        try {
            $model->deleteOrFail();
            DB::commit();
            return Messages::success();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return Messages::failed($exception);
        }
    }

    /**
     * Request params:
     *      cols=>array|nullable,
     *      filter=>string|nullable
     *      limit=>number|nullable
     *      all=>boolean (when true, all records will be returned)
     * @param Builder $builder
     * @param Request $request
     * @return Collection|array
     */
    public static function options(Builder $builder, Request $request): Collection|array
    {
        return $builder
            ->when($request->input("filter"), fn(Builder $query, string $filter) => $query->search($filter))
            ->when(!$request->input("all"), function (Builder $query) use ($request) {
                $query->limit($request->input("limit") ?: 25);
            })
            ->select($request->input("cols") ?: ['*'])
            ->get();
    }
}
