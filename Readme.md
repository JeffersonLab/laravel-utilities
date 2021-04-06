# LaravelUtilities

## Prerequisites
 - PHP >= 7.2.5
 - Laravel 7|8

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Installation

Include the following in your composer.json

~~~~
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/JeffersonLab/laravel-utilities.git"
    }
],
"require" : {
    "jlab/laravel-utilities" : "^8.0"
}
~~~~


Then
`"composer update jlab/laravel-utilities"`

## Testing

``` bash
$ composer update
$ vendor/bin/phpunit 
```

## Package Contents

### Validation Rules

The following validation rules are provided

**InConfig**

The field under validation must be a member of the named configuration parameter.
The configuration parameter may be specified using laravel's dot-array notation. Multi-level arrays will be searched as flat arrays for validation purposes. 

Examples

```php
// The app.php config contains
// 'status_codes' = ['foo','bar'],

//The validation rule can be accessed via string short-hand
$rules = [
  'inputStatusCode' => 'inConfig:app.status_codes'
];

//or via the underlying InConfig rule class
$rules = [
  'inputStatusCode' => new InConfig('app.status_codes'),
];

```

### BaseModel Class

#### Usage

Create a class that extends BaseModel and then place any desired [validation rules](https://laravel.com/docs/validation#available-validation-rules) into $rules.
``` php
class MyModel extends BaseModel
{
    public static $rules = [
        'username' => 'required | max:8',
    ];
}
```

Calls to save() will return false if the model attributes do not validate successfully against $rules.  The errors() method will return a laravel MessageBag containing validation error messages:

``` php
$model = new MyModel(['username'=>'longerthan8chars']);
$model->save();       // false
$model->hasErrors();  // true
print_r($model->errors()->toArray(); // show the errors  
```

If necessary, there are two ways to save the model regardless of whether it validates or not.

The first is by setting mustValidate to false.  In this scenario the validation still occurs, but does not prevent save from proceeding if possible.

``` php
$model = new MyModel(['username'=>'longerthan8chars']);
$mode->mustValidate = false;
$model->save();       // true
$model->hasErrors();  // true
print_r($model->errors()->toArray(); // show the errors  
```

The second is by calling quickSave()
``` php
$model = new MyModel(['username'=>'longerthan8chars']);
$model->quickSave();       // true
$model->hasErrors();       // false only b/c we didn't check!  
```

Of course when bypassing validation checks, there is even more onus is on the user to handle outcomes such as database exceptions that might be caused by trying to save bad data.


## Contributing

Contributions are neither sought nor desired at this time.

