<?php

   namespace Bitphp\Http;

   class Request
   {
      public static $requested_uri;
      public static $requested_uri_array;
      public static $remote_addr;

      private static $standard = null;
      private static $headers  = null;

      
      private static function filter($index, $method, $filter) 
      {
         $filter = $filter ? FILTER_SANITIZE_FULL_SPECIAL_CHARS : FILTER_DEFAULT;
         return filter_input($method, $index, $filter);
      }

      public static function uriValue($index) 
      {
         $parms = self::uriArray();

         if(is_numeric($index)) {
            if(!isset($parms[$index]))
               return null;
        
            $result = $parms[$index];
         } else {
            $index = array_search($index, $parms);
            $result = self::url($index + 1);
         }

         return $result;
      }

      public static function post($index, $filter=true) 
      {
         return self::filter($index, INPUT_POST, $filter);
      }

      public static function get($index, $filter=true) 
      {
         return self::filter($index, INPUT_GET, $filter);
      }

      public static function cookie($index, $filter=true) 
      {
         return self::filter($index, INPUT_COOKIE, $filter);
      }

      public static function standard($index, $filter=true) 
      {
         if(null === self::$standard) {
            $input = file_get_contents('php://input');
            parse_str($input, self::$standard);
         }

         if(!isset(self::$standard[$index]))
            return null;

         if(!$filter)
            return self::$standard[$index];

         return filter_var(self::$standard[$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      }

      public static function headers($index, $filter=true) 
      {
         if(null === self::$headers) {
            self::$headers = getallheaders();
         }

         $index = ucwords(strtolower($index),'-');

         if(!isset(self::$headers[$index]))
            return null;

         if(!$filter)
            return self::$headers[$index];

         return filter_var(self::$headers[$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      }

      public static function uri()
      {
         if(self::$requested_uri)
            return self::$requested_uri;

         $uri = filter_input(INPUT_GET, '_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         self::$requested_uri = empty($uri) || (strlen($uri) == 1) ? '/' : rtrim($uri, '/');

         return self::$requested_uri;
      }

      public static function uriArray()
      {
         if(null !== self::$requested_uri_array)
            return self::$requested_uri_array;

         $array = trim(self::uri(), '/');
         $array = explode('/', $array);

         if (!empty($array)) {
            self::$requested_uri_array = $array;
            return $array;
         }

         self::$requested_uri_array = array();
         return self::$requested_uri_array;
      }

      public static function ip()
      {
         if(self::$remote_addr)
            return self::$remote_addr;

         self::$remote_addr = $_SERVER['REMOTE_ADDR'];

         return self::$remote_addr;
      }

      public static function method()
      {
         return $_SERVER['REQUEST_METHOD'];
      }
   }