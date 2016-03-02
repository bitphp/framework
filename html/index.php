<?php

require '../autoload.php';

use \Bitphp\Route;

Route::match('GET /hello/:name', 'home@index');