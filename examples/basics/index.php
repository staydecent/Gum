<?php
// php -S localhost:8001 index.php

require '../../src/Gum/Singleton.php';
require '../../src/Gum/Event.php';
require '../../src/Gum/Route.php';
require '../../src/Gum/Response.php';

use Gum\Event as Event;
use Gum\Route as Route;
use Gum\Response as Response;

// Hooks

/**
 * Auth
 *
 * @param array $data route and matches
 *
 * @return void
 */
function auth($data)
{
    $checkAuth = strpos($data['route'], '/settings') === 0;
    if ($checkAuth) {
        header('HTTP/1.0 401 Unauthorized');
        echo '401 Unauthorized';
        exit;
    }
}

Event::hook('before_callback', auth);

// Handlers

/**
 * Homepage content
 *
 * @return void
 */
function home() 
{
    echo Response::render('views/home');
}

/**
 * Settings
 *
 * @return void
 */
function settings() 
{
    echo Response::render('views/home');
}

// URLs

Route::get('/', home);
Route::get('/settings', settings);

if (Route::notFound()) {
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    exit;
}
