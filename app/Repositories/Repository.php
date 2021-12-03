<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository 
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function withRelationship($attributes)
    {
        $this->model = $this->model->with($attributes);
    }

    public function withFilters($filters)
    {
        $filters = explode(';', $filters);
        foreach ($filters as $filter) {
            if ($filter == null) {
                break;
            }
            $where = explode(',', $filter);
            $this->model = $this->model->where($where[0], $where[1], $where[2]);
        }
    }

    public function withAttributes($attributes)
    {
        $this->model = $this->model->selectRaw($attributes);
    }

    public function getResults()
    {
        return $this->model->get();
    }
}