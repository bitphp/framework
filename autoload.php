<?php
/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
$loader = null;

// if composer loader exists, use it
if(file_exists('../vendor/autoload.php')) {
    $loader = require '../vendor/autoload.php';
} else {
   // if not, use the bitphp's loader
    $loader = require '../bitphp/autoload.php';
}