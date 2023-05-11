<?php


namespace App\Services;

use App\Models\Role;
use App\Repositories\UserRepository;

class UserService extends BaseService
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
        $this->filter_fields = [
            'name' => ['type' => 'string'],
            'role_id' => ['type' => 'number']
        ];
    }
}
