<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 2/25/19
 * Time: 2:07 PM
 */

namespace Jlab\LaravelUtilities\Rules;


use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Rule as RuleContract;


class InConfig implements RuleContract
{
    /** @var array */
    protected $validValues;

    /** @var string */
    protected $attribute;


    public function __construct($configField)
    {
        // Note that in Laravel 5.8, the array_wrap and array_flatten helpers used
        // below will become deprecated.
        // @see https://laravel-news.com/laravel-5-8-deprecates-string-and-array-helpers
        $values =  Arr::wrap(config(trim($configField),[]));
        $this->validValues = Arr::flatten($values);
    }

    public function passes($attribute, $value): bool
    {
        // Store for later error message generation
        $this->attribute = $attribute;

        // Once $validValues is a normal array of values,
        // we can reuse the Laravel validation "in" rule validator logic instead of reinventing it.
        return $this->validator($value)->passes();
    }

    public function message(): string
    {
        return str_replace(':attribute',$this->attribute, ':attribute does not equal a valid configuration value');
    }

    protected function validator($value){
        return resolve('validator')->make($this->data($value), $this->rule());
    }

    protected function data($value){
        return [
            $this->attribute => $value
        ];
    }

    protected function rule(){
        return [$this->attribute => Rule::in($this->validValues)];
    }

}
