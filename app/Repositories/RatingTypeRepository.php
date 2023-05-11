<?php


namespace App\Repositories;

use App\Models\RatingType;
use App\Repositories\BaseRepository;

class RatingTypeRepository extends BaseRepository
{
    public function __construct(RatingType $entity)
    {
        $this->entity = $entity;
    }

}
