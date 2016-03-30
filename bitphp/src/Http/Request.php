<?php
namespace Bitphp\Http;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Request
{
   protected static $standard = null;
   protected static $headers  = null;

   protected static function filter($index, $method, $filter) 
   {
      $filter = $filter ? FILTER_SANITIZE_FULL_SPECIAL_CHARS : FILTER_DEFAULT;
      return filter_input($method, $index, $filter);
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

   public static function input($index, $filter=true) 
   {
      if(null === self::$standard) 
      {
         $input = file_get_contents('php://input');
         parse_str($input, self::$standard);
      }

      if(!isset(self::$standard[$index])) return null;
      if(!$filter) return self::$standard[$index];

      return filter_var(self::$standard[$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   }

   public static function header($index, $filter=true) 
   {
      if(null === self::$headers)
         self::$headers = getallheaders();

      $index = ucwords(strtolower($index),'-');

      if(!isset(self::$headers[$index])) return null;
      if(!$filter) return self::$headers[$index];

      return filter_var(self::$headers[$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   }
}