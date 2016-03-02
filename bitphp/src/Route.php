<?php

   namespace Bitphp;

   class Route
   {
      protected static $requested_uri;
      protected static $served = false;
      protected static $route_group = array();
      protected static $route_group_depth = 0;

      protected static function createUriPattern($route)
      {
         if(strlen($route) > 1)
            $route = rtrim($route, '\/');

         $search = ['/\//', '/(\:\w+)/'];
         $replace = ['\/', '(.*)'];
         return '/^' . preg_replace($search, $replace, $route) . '$/';
      }

      protected static function matchMethod($method)
      {
         $method  = strtoupper($method);
         $rmethod = $_SERVER['REQUEST_METHOD'];

         return $method == $rmethod ? true : false;
      }

      protected static function requestUri()
      {
         if(self::$requested_uri !== null)
            return self::$requested_uri;

         $uri = filter_input(INPUT_GET, '_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $uri = empty($uri) || (strlen($uri) == 1) ? '/' : rtrim($uri, '/');
         return (self::$requested_uri = $uri);
      }

      protected static function grouping($uri)
      {
         if(empty(self::$route_group))
            return $uri;

         return implode('', self::$route_group) . $uri;
      }

      public static function group($name, $callback) 
      {
         if(self::$served)
            return;

         self::$route_group[self::$route_group_depth] = $name;
         
         self::$route_group_depth++;         
         call_user_func($callback);
         self::$route_group_depth--;

         unset(self::$route_group[self::$route_group_depth]);
      }

      public static function match($route, $callback)
      {
         if(self::$served)
            return;

         list($method, $uri) = explode(' ', $route);
         
         if(!self::matchMethod($method))
            return;

         $ruri = self::requestUri();
         $uri = self::grouping($uri);
         $pattern = self::createUriPattern($uri);

         if(preg_match($pattern, $ruri, $matches)) 
         {
            array_shift($matches);
            
            if(is_callable($callback)) {
               call_user_func_array($callback, $matches);
            } else {
               list($controller, $func) = explode('@', $callback);
               forward_static_call_array(array('\\Controllers\\' . ucfirst($controller), $func), $matches);
            }

            self::$served = true;
         }
      }

      public static function ifNotMatch($callback) {
         if(self::$served)
            return;

         call_user_func($callback);
      }
   }