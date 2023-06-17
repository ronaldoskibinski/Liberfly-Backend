<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Repositories\BaseCrudRepository;

abstract class CrudService {
    /**
    * Repository class for ServiceCrud.
    *
    * @var string
    */
    protected $repositoryClass;

    /**
    * BaseCrudRepository for ServiceCrud.
    *
    * @var BaseCrudRepository
    */
    public $repository;


    public function __construct()
    {
        $this->repository = new $this->repositoryClass();
    }

    public function save($data, $callback = null) {
        try {
            return $this->repository->save($data, $callback);
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao salvar no banco de dados!",
                "message_original" => $th->getMessage(),
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }

    public function update($data, $id, $callback = null) {
        try {
            return $this->repository->update($data, $id, $callback);
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao alterar no banco de dados!",
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }

    public function delete($id) {

        try {
            return [
                "success" => $this->repository->delete($id)
            ];
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao excluir no banco de dados!",
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }

    public function showAudit($id)
    {
        try {
            /** @var BaseModel */
            $model = $this->repository->findByID($id, true, ['audits']);

            return $model->audits;
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao recuperar registros de auditoria!",
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }
}
