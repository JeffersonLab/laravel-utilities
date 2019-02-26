# Jlab/LaravelUtilities
## Helpful Utilities for Laravel Web Apps

### Prerequisites
 - PHP >= 7.1
 - Laravel 5.7
 
### Version History

| Version | Comment |
| ----- | ----- |
| 0.1 | provides in_config custom validation rule |


### Installation

Include the following in your composer.json

~~~~
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/JeffersonLab/laravel-utilities.git"
    }
],
"require" : {
    "jlab/laravel-utilities" : "^0.1"
}
~~~~


Then
`"composer update jlab/laravel-utilities"`



### Package Contents

#### Validation Rules

The following validation rules are provided

**InConfig**

The field under validation must be a member of the named configuration parameter.
The configuration parameter may be specified using laravel's dot-array notation. Multi-level arrays will be searched as flat arrays for validation purposes. 

Examples

```php
//The app.php config contains
'status_codes' = ['foo','bar'],

//The validation rule can be accessed via string short-hand
$rules = [
  'inputStatusCode' => 'inConfig:app.status_codes'
];

//or via the underlying InConfig rule class
$rules = [
  'inputStatusCode' => new InConfig('app.status_codes'),
];

```
