<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserSchoolRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService extends CrudService
{
    protected $repositoryClass = UserRepository::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($dados)
    {
        DB::beginTransaction();
        try {
            $model = $this->repository->save($dados, function ($model, $data) {
                $model->password = Hash::make($data['password']);
            });
            DB::commit();

            return $model;
        } catch (\Throwable $th) {
            DB::rollback();
            throw new Exception($th->getMessage());
        }
    }

    public function reset($data)
    {
        $dadosReset = $this->repository->reset($data);

        if (empty($dadosReset['front_url'])) {
            return [
                "success" => false,
                "message" => "Favor adicionar a url do front na tabela de configuracoes com o nome front_url.",
            ];
        }

        if ($dadosReset["success"]) {
            try {
                Mail::to($dadosReset["email"])->send(new ResetPasswordMail($dadosReset));

                return [
                    "success" => true,
                    "message" => "Email enviado com sucesso",
                ];
            } catch (\Throwable $th) {
                return [
                    "success" => false,
                    "message" => "Erro ao enviar e-mail para o usuário! Verifique as credenciais",
                    "original_message" => $th->getMessage(),
                    "trace" => env("APP_DEBUG", false) ? $th->getTraceAsString() : "",
                ];
            }
        }

        return [
            "success" => $dadosReset["success"],
            "email" => $data["email"],
            "message" => $dadosReset["message"] ?? "Erro ao enviar e-mail para o usuário! Verifique as credenciais",
        ];
    }
}
