<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService extends CrudService
{
    protected $repositoryClass = ProductRepository::class;

    public function __construct()
    {
        parent::__construct();
    }
}
