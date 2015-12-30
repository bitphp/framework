<?php

   require 'bitphp/autoload.php';

   use \Bitphp\Base\Server;

   $server = new Server();

   $server
      
      ->doGet(
           '/'
         , function() {
            echo "Hello world!";
         });

   $server->run();