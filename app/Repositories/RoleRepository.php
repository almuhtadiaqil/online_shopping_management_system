<?php
namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model = null)
    {
        $this->model = $model;
    }
}
