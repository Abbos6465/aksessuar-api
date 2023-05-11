<?php


namespace App\Repositories;

use App\Models\CompanyAnnouncement ;
use App\Repositories\BaseRepository;

class CompanyAnnouncementRepository extends BaseRepository
{
    public function __construct(CompanyAnnouncement $entity)
    {
        $this->entity = $entity;
    }

}
