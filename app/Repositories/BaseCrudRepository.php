<?php

namespace App\Repositories;

abstract class BaseCrudRepository extends BaseRepository
{
    public function buildModel() {
        return new $this->modelClass();
    }

    public function save(array $data, $callBack = null)
    {
        $model = new $this->modelClass();
        $model->fill($data);

        if (isset($callBack)) {
            $callBack($model, $data);
        }

        $model->save();

        return $model;
    }

    public function update(array $data, $id, $callBack = null)
    {
        $model = $this->findByID($id);
        $model->fill($data);

        if (isset($callBack)) {
            $callBack($model, $data);
        }

        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->findByID($id);
        if ($model)
        {
            return $model->delete();
        }
    }
}
