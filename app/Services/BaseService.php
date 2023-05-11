<?php


namespace App\Services;


use Carbon\Carbon;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService
{
    /**
     * @var
     */
    protected BaseRepository $repo;
    /**
     * @var
     */
    protected $listRelation;
    /**
     * @var
     */
    protected $showRelation;
    /**
     * @var
     */
    protected $attributes;
    /**
     * @var
     */
    protected $sort_fields;

    /**
     * @var
     */
    protected $filter_fields;

    /**
     * @var
     */
    protected $showAppends;

    /**
     * @var
     */
    protected $listAppends;

    /**
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $perPage = $params['per_page'] ?? 20;
        $query = $this->repo->getQuery();
        $query = $this->relation($query, $this->listRelation, $params);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->sort($query, $params, $this->sort_fields);
        $query = $this->select($query, $this->attributes);
        return $this->repo->getPaginate($query, $perPage, $this->listAppends);
    }

    /**
     * @param Builder $query
     * @param null $relation
     * @return Builder
     */
    public function relation(Builder $query, $relation = null, $params = []) : Builder
    {
        if ($relation) {
            $query->with($relation);
        }
        if(isset($params['relations']) && $params['relations']) {
            $relations = explode('|', $params['relations']);
            foreach ($relations as $relation) {
                $query->with($relation);
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param null $attributes
     * @return Builder
     */
    public function select(Builder $query, $attributes = null) : Builder
    {
        if ($attributes) {
            $query->select($attributes);
        }
        return $query;
    }


    /**
     * @param Builder $query
     * @param $filter_fields
     * @param $params
     * @return Builder
     */
    public function filter(Builder $query, $filter_fields, $params) : Builder
    {
        foreach ($filter_fields as $key => $item) {
            if (array_key_exists($key, $params)) {

                if ($item['type'] == 'string') {
                    if(isset($item['is_relation'])) {
                        $arr = explode('_', $key);
                        $relation = $arr[0];
                        $column = $arr[1] . (isset($arr[2]) ? '_'.$arr[2] : '');
                        $query->whereHas($relation, function ($q) use ($column, $params, $key){
                            $q->where($column, 'ilike', '%' . $params[$key] . '%');
                        });
                    } else {
                        $query->where($key, 'ilike', '%' . $params[$key] . '%');
                    }
                }

                if ($item['type'] == 'number') {
                    $search = preg_replace('/\s+/', '', (string) $params[$key]);
                    if(isset($item['is_relation'])) {
                        $arr = explode('_', $key);
                        $relation = $arr[0];
                        $column = $arr[1] . (isset($arr[2]) ? '_'.$arr[2] : '');
                        $query->whereHas($relation, function ($q) use ($column, $search, $key){
                            if($search == 'null') {
                                $q->whereNull($column);
                            } else {
                                $q->where($column, $search);
                            }
                        });
                    } else {
                        if($search == 'null') {
                            $query->whereNull($key);
                        } else {
                            $query->where($key, $search);
                        }
                    }
                }
                if ($params[$key] and $item['type'] == 'json') {
                    if ($item['search'] == 'string')
                        $query->where('data->' . $key . '', 'ilike', $params[$key]);
                    if ($item['search'] == 'number')
                        $query->where('data->' . $key . '', $params[$key]);
                }
                if ($params[$key] and $item['type'] == 'date') {
                    $query->whereDate($key, Carbon::parse($params[$key])->format('Y-m-d'));
                }
            }
        }
        return $query;
    }

    /**
     * @param $query
     * @param array $params
     * @return Builder
     */
    public function sort($query, array $params, $sort_fields = null): Builder
    {
        $key = 'id';
        $order = 'desc';
        if (isset($sort_fields) and isset($sort_fields['sort_key'])) {
            $key = $sort_fields['sort_key'];
            $order = $sort_fields['sort_type'];
        }
        if (isset($params['sort_key'])) {
            $key = $params['sort_key'];
            $order = $params['sort_type'];
        }
        $query->orderBy($key, $order);

        return $query;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function create($params)
    {
        return $this->repo->store($params);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $model = $this->repo->getQuery();
        if($this->showRelation && count($this->showRelation)) {
            foreach ($this->showRelation as $relation) {
                $model->with($relation);
            }
        }

        if(request('relations')) {
            $relations = explode('|', request('relations'));
            foreach ($relations as $relation) {
                $model->with($relation);
            }
        }
        $model = $model->find($id);
        if($model) {
            if($this->showAppends && count($this->showAppends)) {
                foreach ($this->showAppends as $append) {
                    $model->$append = $model->$append;
                }
            }
            if(request('appends')) {
                $appends = explode('|', request('appends'));
                foreach ($appends as $append) {
                    $model->append($append);
                }
            }
        }
        return $model;
    }

    /**
     * @param $params
     * @param $id
     * @return mixed
     */
    public function update($params, $id)
    {
        return $this->repo->update($params, $id);

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->repo->destroy($id);
    }

    public function buildTree(array &$elements, $parentId = null, $parentKey = 'parent_id', $key = 'id') {
        $branch = array();

        foreach ($elements as $element) {
            if ($element[$parentKey] == $parentId) {
                $children = $this->buildTree($elements, $element[$key], $parentKey, $key);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
