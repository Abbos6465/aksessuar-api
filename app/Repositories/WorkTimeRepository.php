<?php


namespace App\Repositories;

use App\Models\WorkTime ;
use App\Repositories\BaseRepository;

class WorkTimeRepository extends BaseRepository
{
    public function __construct(WorkTime $entity)
    {
        $this->entity = $entity;
    }

}
