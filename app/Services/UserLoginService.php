<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserLoginRepository;
use Carbon\Carbon;

class UserLoginService extends CrudService
{
    protected $repositoryClass = UserLoginRepository::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function updateEndDateUserLastLogin($dataEnd)
    {
        try {
            $userLogin = $this->repository->buildModel()
                ->where('fk_user', auth()->id())
                ->orderBy('id', 'DESC')
                ->firstOrFail();

            return $this->repository->update([
                'data_end' => $dataEnd
            ], $userLogin->id);
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Erro ao alterar no banco de dados!",
                "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : ""
            ];
        }
    }
}
