<?php

namespace Jlab\LaravelUtilities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

/**
 * LaravelBaseModel - self-validating Eloquent model class
 */
abstract class BaseModel extends Model
{
    /**
     * An array of laravel validation rules
     * https://laravel.com/docs/validation#available-validation-rules
     * @var array
     */
    public static $rules = array();

    /**
     * An array of laravel validation error messages
     * @see https://laravel.com/docs/validation#custom-error-messages
     * @var array
     */
    public static $messages = array();

    /**
     * Prevent model save when validation fails.
     *
     * When set to true the save method will only proceed if validate method returns true.
     * When set to false, the save method will proceed regardless of validate result.
     * In either case, the error messages from the validation attempt will be available via errors().
     *
     * @var array
     */
    public $mustValidate = true;


    /**
     * A message bag instance containing validation error messages
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $validationErrors;


    /**
     * Create a new LaravelBaseModel instance.
     *
     * @param array $attributes
     *
     * @return BaseModel
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->validationErrors = new MessageBag();
    }

    /**
     * Returns the model's validation rules.
     *
     * Child classes can override this method in order define validation rules
     * that cannot be captured completelty in the static $rules parameter.  For
     * example rules that rely upon dynamic
     *
     * @return array
     */
    public function rules()
    {
        return static::$rules;
    }

    /**
     * Returns the model's validation error messages.
     *
     * Child classes can override this method in order define custom error messages
     * to go with custom rules.
     *
     * @return array
     */
    public function messages(){
        return static::$messages;
    }

    /**
     * Register a validating model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string|array  $callback
     * @return void
     */
    public static function validating($callback)
    {
        static::registerModelEvent('validating', $callback);
    }

    /**
     * Register a validating model event with the dispatcher.
     *
     * @param  \Illuminate\Events\QueuedClosure|\Closure|string|array  $callback
     * @return void
     */
    public static function validated($callback)
    {
        static::registerModelEvent('validated', $callback);
    }

    /**
     * Returns a Validator to validate the object.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidator(){
        return Validator::make($this->attributes, $this->rules(), $this->messages());
    }

    /**
     * Validates and then saves the model.
     *
     * @return bool true if save succeeded
     */
    public function save(array $options = [])
    {
        if (!$this->validate() && $this->mustValidate) {
            return false;
        }
        return parent::save($options);
    }

    /**
     * Validates the model.
     *
     * Error messages from failed validation can be fetched with @link errors
     */
    public function validate()
    {
        // If the "validating" event returns false we'll bail out of the validation and return
        // false, indicating that the validation did not occur. This provides a chance for any
        // listeners to cancel validation.
        if ($this->fireModelEvent('validating') === false) {
            return false;
        }

        $validator = $this->getValidator();
        if ($validator->passes()) {
            $this->validationErrors = new MessageBag();
            return $this->finishValidate(true);
        } else {
            $this->validationErrors = $validator->errors();
        }
        return $this->finishValidate(false);
    }

    protected function finishValidate($result){
        $this->fireModelEvent('validated');
        return $result;
    }

    /**
     * Answers whether the provided value for the named attribute passes the model's
     * validation rules for that attribute.  Returns true if no validation rule exists
     * for the named attribute.
     *
     * @param $attributeName
     * @param $value
     * @return bool
     */
    public function isValidAttributeValue($attributeName, $value)
    {
        if (array_key_exists($attributeName, static::$rules)) {
            $validator = Validator::make(Arr::wrap($value), Arr::wrap(static::$rules[$attributeName]));
            return $validator->passes();
        }
        return true;
    }


    /**
     * Saves the model bypassing validation entirely.
     *
     * @param array $options
     *
     * @return bool
     */
    public function quickSave(array $options = [])
    {
        return parent::save($options);
    }

    /**
     * Returns the errors message bag.
     *
     * @return MessageBag
     */
    public function errors()
    {
        return $this->validationErrors;
    }

    /**
     * Are there validation error messages available?
     *
     * @return bool
     */
    public function hasErrors(){
        return $this->validationErrors->isNotEmpty();
    }

}
