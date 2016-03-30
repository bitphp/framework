<?php
namespace Bitphp;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Url
{
   private static $base_url;
   private static $requested_uri;

   public static function base()
   {
      if(self::$base_url) return self::$base_url;

      $dirname   = dirname($_SERVER['PHP_SELF']);
      $base_url  = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
      $base_url .= $_SERVER['SERVER_NAME'];
      $base_url .= $dirname == '/' ? '' : $dirname;
         
      return (self::$base_url = $base_url);
   }

   public static function path()
   {
      if(self::$requested_uri !== null) return self::$requested_uri;

      $uri = filter_input(INPUT_GET, '_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $uri = empty($uri) || (strlen($uri) == 1) ? '/' : rtrim($uri, '/');
         
      return (self::$requested_uri = $uri);
   }
}