<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository
{
    public function __construct(User $model = null)
    {
        $this->model = $model;
    }

    public function store(Request $request)
    {
        $model = $this->model->fill($request->all());
        $model->setPassword();
        $model->save();
        return $model;
    }
}
