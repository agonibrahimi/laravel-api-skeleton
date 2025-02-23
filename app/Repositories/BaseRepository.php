<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function getAll($paginate = 1000) {
        return $this->model->paginate($paginate);
    }

    public function findById($id) {
        return $this->model->find($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }

    public function update($id, array $data) {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete($id) {
        $record = $this->findById($id);
        $record->delete();
        return true;
    }
}
