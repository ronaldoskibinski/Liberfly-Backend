<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseCrudRepository
{
    protected $modelClass = Product::class;

    public function __construct() {
        parent::__construct();
    }
}
