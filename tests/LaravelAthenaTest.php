<?php

namespace Olekjs\LaravelAthenaPdf\Tests;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\View;
use Olekjs\LaravelAthenaPdf\LaravelAthenaPdf;

class LaravelAthenaTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $app = new Container();
        $app->singleton('app', 'Illuminate\Container\Container');

        $app->singleton('config', 'Illuminate\Config\Repository');


        View::addLocation(__DIR__.'/sample/views');
        $this->emptyTempDirectory();
    }

    protected function getEnvironmentSetUp($app)
    {

    }

    /** @test */
    public function it_can_load_view()
    {
        $html = new LaravelAthenaPdf();
        $html->loadView('sample_view', ['test' => 'test']);
        //todo: check
    }
}
