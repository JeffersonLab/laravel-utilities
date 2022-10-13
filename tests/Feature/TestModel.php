<?php

namespace Tests\Feature;

use Jlab\LaravelUtilities\BaseModel;

class TestModel extends BaseModel
{

    public $timestamps = false;

    public $eventLog = [];

    public $fillable = [
        'username'
    ];

    public static $rules = [
        'username' => 'required | max:8',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // Logs the events that were triggered so that tests can verify they occurred.
        static::validating(function ($model) {
            $model->eventLog[] = 'validating';
        });
        static::validated(function ($model) {
            $model->eventLog[] = 'validated';
        });
    }
}
