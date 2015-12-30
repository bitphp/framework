<?php

   namespace Bitphp\Core;

   class Mvc
   {
      public static function controller($controller)
      {
         $controller = ucfirst($controller);

         $controller_file  = Route::basepath() . "/app/controllers/$controller.php";
         $controller_class = '\\App\\Controllers\\' . $controller;

         if(!file_exists($controller_file)) {
            trigger_error("Controller $controller don't exists", E_USER_ERROR);
            return false;
         }

         return new $controller_class;
      }

      public static function view($view)
      {
         $view = Route::basepath() . "/app/views/sources/$view.php";

         if(!file_exists($view))
            return false;

         return file_get_contents($view);
      }
   }