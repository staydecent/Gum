<?php 

// php -S localhost:8001 index.php

require '../../src/Gum/Singleton.php';
require '../../src/Gum/Event.php';
require '../../src/Gum/Route.php';

use Gum\Route as Route;

Route::get('thing', function() {
    echo "Thing";
});

if (Route::not_found())
{
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    exit;
}