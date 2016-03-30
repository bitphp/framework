<?php
namespace Bitphp;

use  \Bitphp\Url;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Route
{
   protected static $served = false;
   protected static $route_group = array();
   protected static $route_group_depth = 0;

   protected static function createUriPattern($route)
   {
      if(strlen($route) > 1)
         $route = rtrim($route, '\/');

      $search = [
           '/\//'
         , '/(str\$\w+)/'
         , '/(int\$\w+)/'
         , '/(\$\w+)/'
      ];
         
      $replace = [
           '\/'
         , '(\w+)'
         , '([0-9]+)'
         , '(.*)'
      ];

      return '/^' . preg_replace($search, $replace, $route) . '$/';
   }

   protected static function matchMethod($method)
   {
      $rmethod = $_SERVER['REQUEST_METHOD'];
      return strtoupper($method) == $rmethod ? true : false;
   }

   protected static function grouping($uri)
   {
      if(empty(self::$route_group)) 
         return $uri;
         
      return implode('', self::$route_group) . $uri;
   }

   public static function group($name, $callback) 
   {
      if(self::$served) return;

      self::$route_group[self::$route_group_depth] = $name;         
      self::$route_group_depth++;         
      call_user_func($callback);
      self::$route_group_depth--;

      unset(self::$route_group[self::$route_group_depth]);
   }

   public static function match($route, $callback)
   {
      if(self::$served) return;

      list($method, $uri) = explode(' ', $route);
         
      if(!self::matchMethod($method)) return;

      $ruri = Url::path();
      $uri  = self::grouping($uri);
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
      if(self::$served) return;
      call_user_func_array($callback, array(Url::path()));
   }
}