<?php
// Routes

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/chinese', 'HomeController:changeLang');

$app->get('/apod', 'HomeController:apodImage');

$app->get('/{statusCode}', 'HomeController:imageReq');

$app->get('/', 'HomeController:home');
