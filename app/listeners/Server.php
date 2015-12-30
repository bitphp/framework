<?php

   namespace App\Listeners;

   use \Bitphp\Core\Config;

   class Server 
   {
      public static function onStartup() 
      {
         Config::load('application');
      }
   }