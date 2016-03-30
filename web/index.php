<?php

require '../autoload.php';

use \Bitphp\Route;
use \Bitphp\Layout\Medusa;

Route::match('GET /', function() {
   Medusa::draw('welcome');
});