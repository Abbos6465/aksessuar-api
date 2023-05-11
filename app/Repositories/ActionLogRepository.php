<?php


namespace App\Repositories;

use App\Models\ActionLog ;
use App\Repositories\BaseRepository;

class ActionLogRepository extends BaseRepository
{
    public function __construct(ActionLog $entity)
    {
        $this->entity = $entity;
    }

}
