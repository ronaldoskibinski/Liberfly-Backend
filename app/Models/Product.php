<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $table = "products";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'value'
    ];

    public $rules = [
        'name' => 'required',
        'code' => 'required',
        'value' => 'required',
    ];

    public $messages = [
        'name' => 'Nome',
        'code' => 'Código',
        'value' => 'Valor',
    ];
}
