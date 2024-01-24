<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseRepository
{
    protected Model $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function retrieved(Request $request)
    {
        return $this->model->query()->get();
    }
    public function getPagination(Request $request)
    {
        return $this->model
            ->query()
            ->paginate(
                $request->page_size ?? 5,
                ['*'],
                'page',
                $request->page_index ?? 1
            );
    }

    public function get_by_column($column, $value)
    {
        return $this->model
            ->query()
            ->where($column, $value)
            ->first();
    }

    public function store(Request $request)
    {
        return $this->model->fill($request->all())->save();
    }

    public function destroy($column, $value)
    {
        return $this->model
            ->query()
            ->where($column, $value)
            ->delete();
    }

    public function update(Request $request, $column, $value)
    {
        return $this->model
            ->query()
            ->where($column, $value)
            ->update($request->all());
    }
}
