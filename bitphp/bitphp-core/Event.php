<?php

   namespace Bitphp\Core;

   use \Exception;

   class Event 
   {

      public static function trigger($listener, $event, $args = array()) 
      {
         $listener = ucfirst($listener);
         $event = 'on' . ucfirst($event);

         $listener_file  = Route::basepath() . "/app/listeners/$listener.php";
         $listener_class = '\\App\\Listeners\\' . $listener;

         if(!file_exists($listener_file))
            return false;

         if(!method_exists($listener_class, $event))
            return false;

         return forward_static_call_array(array($listener_class, $event), $args);
      }
   }