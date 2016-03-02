<?php 

   namespace Bitphp;

   class Config 
   {
      protected static $params;

      protected static function load() 
      {
         if(self::$params !== null)
           return;

         $file = '../app/config.json';

         if(file_exists($file)) {
            $content = file_get_contents($file);
            self::$params = json_decode($content, true);
            return;
         }

         self::$params = array();
      }

      public static function param($index) 
      {
         self::load();
         return isset(self::$params[$index]) ? self::$params[$index] : null;
      }

      public static function set($index, $value) 
      {
         self::$params[$index] = $value;
      }

      public static function all() 
      {
         return self::$params;
      }
   }