<?php

namespace Dan\Prioritree\Test;

use Silex\WebTestCase as BaseTestCase;

class WebTestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;

        $app['session.test'] = true;
        unset($app['exception_handler']);
        //$app['exception_handler']->disable();

        return $app;
    }
}