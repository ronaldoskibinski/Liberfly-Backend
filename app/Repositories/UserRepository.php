<?php

namespace App\Repositories;

use App\Models\Configuracao;
use App\Models\PasswordReset;
use App\Services\PasswordService;
use App\Models\User;
use DateTime;

class UserRepository extends BaseCrudRepository
{
    protected $modelClass = User::class;
    protected $passwordService;

    public function __construct() {
        parent::__construct();
        $this->passwordService = new PasswordService();
    }

    public function newPassword($data) {
        $email = $data["email"];

        if (!$email) {
            return [ 'success' => false, 'message' => 'E-mail não informado!' ];
        }

        $usuario = User::where("email", $email)->first();

        if (!$usuario) {
            return [ 'success' => false, 'message' => 'Usuário não encontrado!' ];
        }

        $passwordReset = PasswordReset::where("email", $email)->first();

        if (!$passwordReset) {
            return [ 'success' => false, 'message' => 'Não foi encontrada solicitação de alteração de senha!' ];
        }


        if (!password_verify($data["token"], $passwordReset->token)) {
            return [ 'success' => false, 'message' => 'Token informado é inválido!' ];
        }

        if ($data["senha"] != $data["confirmar_senha"]) {
            return [ 'success' => false, 'message' => 'Senhas informadas diferem!' ];
        }

        $usuario->password = bcrypt($data["senha"]);
        $usuario->update();

        PasswordReset::where("email", $email)->delete();

        return [ 'success' => true, "usuario" => $usuario ];
    }

    public function reset($data): array {
        $email = $data["email"];

        if (!$email) {
            return [ 'success' => false, 'message' => 'E-mail não informado!' ];
        }

        $usuario = User::where("email", $email)->first();

        if (!$usuario) {
            return [ 'success' => false, 'message' => 'Usuário não encontrado!' ];
        }

        $usuario->update(["active" => 2]);

        PasswordReset::where("email", $email)->delete();

        $token = PasswordService::generatePassword(8);

        $dadosPassword = [
            "email" => $email,
            "token" => bcrypt($token),
            "created_at" => new DateTime()
        ];

        $password = PasswordReset::create($dadosPassword);

        $frontUrl = optional(Configuracao::firstWhere('nome', 'front_url'))->valor;

        return [
            "success" => true,
            "email" => $email,
            "name" => $usuario->nome,
            "token" => $token,
            'front_url' => $frontUrl
        ];
    }

    public function getUsersByCompany($companyId)
    {
        return User::where('fk_company', $companyId)->get();
    }
}
