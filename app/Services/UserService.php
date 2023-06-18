<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends CrudService
{
    protected $repositoryClass = UserRepository::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($userData)
    {
        DB::beginTransaction();
        try {
            $model = $this->repository->save($userData, function ($model, $data) {
                $model->password = Hash::make($data['password']);
            });
            DB::commit();

            return $model;
        } catch (\Throwable $th) {
            DB::rollback();
            throw new Exception($th->getMessage());
        }
    }
}
