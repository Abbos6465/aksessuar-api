<?php


namespace App\Repositories;

use App\Models\Classroom;
use App\Repositories\BaseRepository;

class ClassroomRepository extends BaseRepository
{
    public function __construct(Classroom $entity)
    {
        $this->entity = $entity;
    }

}
