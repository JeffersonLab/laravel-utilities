<?php

namespace Tests\Feature;

use Jlab\LaravelUtilities\BaseModel;

class TestModel extends BaseModel
{

    public $timestamps = false;

    public $fillable = [
        'username'
    ];

    public static $rules = [
        'username' => 'required | max:8',
    ];

}
