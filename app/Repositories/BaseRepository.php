<?php


namespace App\Repositories;

class BaseRepository
{
    public $entity;

    public function getQuery()
    {
        return $this->entity->query();
    }

    public function getById($id)
    {
        return $this->entity->find($id);
    }
    public function getPaginate($query, int $perPage = null, $listAppends = null)
    {
        if ($perPage)
            $data = $query->paginate($perPage);
        else
            $data = $query->get();

        if($listAppends && count($listAppends)) {
            foreach ($data->items() as $item) {
                foreach ($listAppends as $listAppend) {
                    $item->append($listAppend);
                }
            }
        }

        if(request('appends')) {
            $appends = explode('|', request('appends'));
            foreach ($data->items() as $item) {
                foreach ($appends as $append) {
                    $item->append($append);
                }
            }
        }
        return $data;
    }

    public function store($params)
    {
        return $this->entity->create($params);
    }

    public function update(array $params, int $id)
    {
        $query = $this->getById($id);
        if ($query) {
            $query->update($params);
            return $query;
        } else {
            return false;
        }
    }

    public function destroy(int $id)
    {
        $entity = $this->getById($id);
        return $entity ? $entity->delete() : false;
    }
}
