<?php

   require 'bitphp/autoload.php';

   use \Bitphp\Base\View;
   use \Bitphp\Base\Server;

   $server = new Server();
   $views  = new View();

   $server
      
      ->doGet(
           '/'
         , function() use ($views) {
            
            $views
               ->load('welcome')
               ->draw();

         });

   $server->run();