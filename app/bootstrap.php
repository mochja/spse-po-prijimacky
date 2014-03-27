<?php

/**
 * @author Jan Mochnak, <janmochnak@icloud.com>
 * @copyright (c) 2014, SPSE-PO
 */

date_default_timezone_set('Europe/Bratislava');

define('APP_DIR', __DIR__);
require __DIR__.'/../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$app = new \Slim\Slim(array(
    'templates.path' => __DIR__.'/../templates',
));

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('spse-po-prijimacky');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => __DIR__.'/../templates/cache',
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

$app->get('/', function () use ($app) {
    $app->render('index.twig');
});

$app->post('/result', function () use ($app) {
    
    $studentCode = $app->request()->post('student_code', NULL);
    
    if ( empty($studentCode) || !preg_match('/^([A-Z]{3})([0-9]{3})$/', trim($studentCode)/*, $matches*/) ) {
        $app->getLog()->error('Wrong or empty student code', (array)$studentCode);
        throw new \Exception('Wrong or empty student code');
    }
    
    $allResults = require __DIR__.'/../tmp/results.php';
    
    $results = array_filter($allResults, function ($item) use ($studentCode) {
        return $item['student_code'] === $studentCode;
    });
    
    $app->render('result.twig', compact('results'));
});

$app->run();
