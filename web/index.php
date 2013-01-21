<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function () use ($app) {
    $file = __DIR__.'/../data/mytasks.yml';
    $builder = new Dan\Prioritree\TaskBuilder();
    $root = $builder->loadFromFile($file);
    file_put_contents($file, $root->getAsYaml());	
    return '<h1>done</h1>';
});

$app->run();