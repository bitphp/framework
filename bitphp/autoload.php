<?php

$loader = null;

// if composer loader exists, use it
if(file_exists('vendor/autoload.php')) {
    $loader = require 'vendor/autoload.php';
} else {
   // if not, use the bitphp's loader
    $loader = require 'bitphp/bitphp-autoload.php';
}