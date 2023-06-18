<?php

namespace App\Models;

use App\Models\User;
use App\Models\BaseModel;

class UserLogin extends BaseModel
{
    protected $table = 'users_logins';

    protected $fillable = [
        'fk_user',
        'data_start',
        'data_end',
        'ip',
        'navigator',
    ];

    public $rules = [
        'fk_user' => 'required',
        'data_start' => 'required',
        'ip' => 'required',
    ];

    public $messages = [
        'fk_user' => 'Usuário',
        'data_start' => 'Data início',
        'ip' => 'IP',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'fk_user');
    }
}
