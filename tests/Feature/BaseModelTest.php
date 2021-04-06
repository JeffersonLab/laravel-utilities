<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BaseModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }


    function test_it_saves_valid_model_without_errors(){
        $model = new TestModel(['username'=>'jdoe']);
        $this->assertTrue($model->save());
        $this->assertFalse($model->hasErrors());
    }

    function test_it_does_not_save_invalid_model_and_has_errors(){
        $model = new TestModel(['username'=>'jdoeistoolongfor8charlimit']);
        $this->assertFalse($model->save());
        $this->assertTrue($model->hasErrors());
    }

    function test_it_can_bypass_validate_but_reports_errors(){
        $model = new TestModel(['username'=>'jdoeistoolongfor8charlimit']);
        $model->mustValidate = false;
        $this->assertTrue($model->save());
        $this->assertTrue($model->hasErrors());
    }

    function test_it_can_bypass_validate_entirely_with_quicksave(){
        $model = new TestModel(['username'=>'jdoeistoolongfor8charlimit']);
        $this->assertTrue($model->quickSave());
        $this->assertFalse($model->hasErrors());  // only we bypassed the check!
    }

}
