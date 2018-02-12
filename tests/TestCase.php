<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    /*------- From: https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da */
    protected function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }
    /*-------- End ----------*/
    protected function signIn($user = null){
        $user = $user ?: create('App\User');
        $this->be($user);
        return $this;
    }
    /*------- From: https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da */
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
    /*-------- End ----------*/
}
