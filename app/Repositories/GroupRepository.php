<?php


namespace App\Repositories;


use App\Models\Group;

class GroupRepository extends BaseRepository
{
    public function __construct(Group $entity)
    {
        $this->entity = $entity;
    }
}
