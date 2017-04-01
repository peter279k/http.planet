<?php

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    // set the template variables
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Location', '/404');
    };
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// register the HomeController
$container['HomeController'] = function($c) {
    $view = $c->get('renderer');
    $logger = $c->get('logger');
    // retrieve the 'view' from the container
    return new HomeController($view, $logger);
};
