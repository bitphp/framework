<?php

   namespace Bitphp\Core;

   class Watchdog
   {
      public static function run($watchdog, $args)
      {
         list($name, $function) = explode('@', $watchdog);

         $name = ucfirst($name);
         $watch_function = 'watch' . ucfirst($function);

         $file  = Route::basepath() . "/app/watchdog/$name.php";
         $class = '\\App\\Watchdog\\' . $name;

         if(!file_exists($file)) {
            trigger_error("Watchdog $name don't exists");
            return false;
         }

         $object = new $class;

         if(!method_exists($object, $watch_function)) {
            trigger_error("Method $watch_function is not member of watchdog $name");
            return false;
         }

         $result = call_user_func_array(array($object, $watch_function), $args);

         if($result !== false)
            return $result;

         $fail_function = 'fail' . ucfirst($function);

         if(!method_exists($object, $fail_function)) {
            trigger_error("Fail method $fail_function of watchdog $name is not implemented");
            return false;
         }

         call_user_func_array(array($object, $fail_function), $args);
         return false;
      }
   }