<?php

namespace Jlab\LaravelUtilities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jlab\LaravelUtilities\Rules\InConfig;


class PackageServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->makeValidatorExtensions();
    }



    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


    }


    /**
     * Make extensions to the laravel built-in validation rules.
     *
     * @return void
     */
    protected function makeValidatorExtensions()
    {

        //------------------------
        //   The in_config Rule
        //------------------------

        // The validator that checks a value is present in a config setting
        Validator::extend('in_config', function ($attribute, $value, $parameters, $validator) {
            $rule = new InConfig($parameters[0]);
            return $rule->passes($attribute, $value);
        });
        // And its custom error message.
        Validator::replacer('in_config', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute,
                ':attribute does not equal a valid configuration value');
        });


    }
}
