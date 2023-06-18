<?php

namespace App\Repositories;

use App\Models\UserLogin;

class UserLoginRepository extends BaseCrudRepository
{
    protected $modelClass = UserLogin::class;

    public function __construct() {
        parent::__construct();
    }
}
