<?php

   namespace Bitphp\Core;

   class Route 
   {

      protected static $base_path;
      protected static $base_url;

      public static function baseUrl() 
      {

         if(self::$base_url)
            return self::$base_url;

         $dirname   = dirname($_SERVER['PHP_SELF']);

         $base_url  = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
         $base_url .= $_SERVER['SERVER_NAME'];
         $base_url .= $dirname == '/' ? '' : $dirname;
         
         self::$base_url = $base_url;

         return self::$base_url;
      }

      public static function link($route)
      {
         $pretty = Config::param('route.pretty-urls');

         if(true === $pretty)
            return self::baseUrl() . $route;

         return self::baseUrl() . "/index.php?_url=$route";
      }

      public static function basepath()
      {
         if(self::$base_path)
            return self::$base_path;

         self::$base_path = realpath('');

         return self::$base_path;
      }
   }