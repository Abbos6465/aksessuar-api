<?php


namespace App\Repositories;


use App\Models\Filial;

class FilialRepository extends BaseRepository
{
    public function __construct(Filial $entity)
    {
        $this->entity = $entity;
    }
}
