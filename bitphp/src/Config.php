<?php 
namespace Bitphp;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Config 
{
   protected static $params;

   protected static function load() 
   {
      if(self::$params !== null) return;

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

   public static function all() 
   {
      self::load();
      return self::$params;
   }
}