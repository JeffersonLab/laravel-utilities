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

/**
 * Rule to validate that a value exists within a specified set of configuration values.
 */
class InConfig implements RuleContract
{
    /** @var array */
    protected $validValues;

    /** @var string */
    protected $attribute;

    /**
     * @param string $configField - the name of the configuration field in dot notation (ex: app.settings)
     */
    public function __construct($configField)
    {
        $values =  Arr::wrap(config(trim($configField),[]));
        $this->validValues = Arr::flatten($values);
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        // Store for later error message generation
        $this->attribute = $attribute;

        // Once $validValues is a normal array of values,
        // we can reuse the Laravel validation "in" rule validator logic instead of reinventing it.
        return $this->validator($value)->passes();
    }

    /**
     * @inheritDoc
     */
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
