<?php

namespace App\Repositories;

use App\Models\User;
use DateTime;

class UserRepository extends BaseCrudRepository
{
    protected $modelClass = User::class;

    public function __construct() {
        parent::__construct();
    }
}
