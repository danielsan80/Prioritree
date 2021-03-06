<?php
use Symfony\Component\HttpFoundation\Response;


$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->get('/', function () use ($app) {
    $now = new \DateTime();
    $key = md5($now->format('Y-m-d H:i:s'));
    return $app['twig']->render('home.html.twig', array(
            'key' => $key,
        ));
})->bind('home');

$app->get('/admin/{id}', function ($id) use ($app) {
    return $app['twig']->render('admin.html.twig', array(
            'id' => $id
        ));
})->bind('admin');

$app->get('/tree/{id}', function ($id) use ($app) {
    $file = __DIR__.'/../data/tree/'.$id;
    if (!file_exists($file)) {
        file_put_contents($file, file_get_contents(__DIR__.'/../data/example'));
    }
    $content = file_get_contents($file);
    
    return new Response($content);
    
})->bind('tree');

$app->put('/tree/{id}', function ($id) use ($app) {
    $file = __DIR__.'/../data/tree/'.$id;
    $oldContent = file_get_contents($file);
    $newContent = $app['request']->getContent();
    $builder = new Dan\Prioritree\Model\TaskBuilder();
    try {
        $newRoot = $builder->loadFromString($newContent);
    } catch (\Exception $e) {
        return new Response($e->getMessage(),400);
    }
    $oldRoot = $builder->loadFromString($oldContent);
    if ($oldRoot->isEqual($newRoot)) {
        return new Response('No Changes',204);
    }
    $newContent = $newRoot->getAsYaml();
    
    file_put_contents($file, $newContent);	
    
    return new Response($newContent);
    
})->bind('tree_put');

return $app;