<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 2/25/19
 * Time: 11:20 AM
 */

namespace Tests\Feature;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Jlab\LaravelUtilities\Rules\InConfig;
use Tests\TestCase;

class ValidatorTest extends TestCase
{

    protected $rules;

    function setUp(): void
    {
        parent::setUp();

        $this->rules = [
            'statusCode' => 'inConfig:status_codes'
        ];

    }

    function test_validator_extension_passes_if_in_config(){
        Config::set('status_codes',['foo', 'bar']);
        $data = ['statusCode' => 'foo'];
        $validator = Validator::make($data, $this->rules);
        $this->assertTrue($validator->passes());
    }

    function test_validator_extension_fails_if_not_in_config(){
        Config::set('status_codes',['foo', 'bar']);
        $data = ['statusCode' => 'baz'];
        $validator = Validator::make($data, $this->rules);
        $this->assertFalse($validator->passes());
        $this->assertEquals('statusCode does not equal a valid configuration value', $validator->errors()->first());
    }

    function test_it_works_when_config_is_scalar_not_array(){
        Config::set('status_codes','scalarfoo');
        $data = ['statusCode' => 'scalarfoo'];
        $validator = Validator::make($data, $this->rules);
        $this->assertTrue($validator->passes());
    }

    function test_it_works_with_nested_array_values(){
        Config::set('status_codes',[
            'level1' =>[
                'level2' => [
                    'foo' => 'nestedfoo'
                ]
            ]
        ]);
        $data = ['statusCode' => 'nestedfoo'];
        $validator = Validator::make($data, $this->rules);
        $this->assertTrue($validator->passes());
    }

    function test_it_validates_using_rule_object(){
        Config::set('status_codes',['foo', 'bar']);
        $rules = [
            'statusCode' => new InConfig('status_codes'),
        ];
        $data = ['statusCode' => 'foo'];
        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
    }

    function test_it_failes_validation_using_rule_object(){
        Config::set('status_codes',['foo', 'bar']);
        $rules = [
            'statusCode' => new InConfig('status_codes'),
        ];
        $data = ['statusCode' => 'baz'];
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
    }



}