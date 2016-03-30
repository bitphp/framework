<?php
namespace Bitphp\Layout;

use \Bitphp\Cache;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Views 
{
   protected static $view_vars = array();

   protected static function source($view)
   {
      $view_file = "../app/views/sources/$view.view.php";

      if(!file_exists($view_file))
         trigger_error("Views: template $view_file do not exists", E_USER_ERROR);

      return file_get_contents($view_file);
   }

   protected function view_include($view) 
   {
      self::draw($view, self::$view_vars);
   }

   protected static function make($view, $vars)
   {
      $cache_id = md5(json_encode(array($view, $vars)));
      $output = Cache::read('views', $cache_id);

      if(false !== $output) return $output;

      self::$view_vars = $vars;
      $source = self::source($view);

      ob_start();
      extract($vars);
      eval("?> $source <?php ");

      Cache::save('views', $cache_id, ($output = ob_get_clean()));
      return $output;
   }

   public static function draw($view, $vars=array())
   {
      echo self::make($view, $vars);
   }

   public static function read($view, $vars=array())
   {
      return self::make($view, $vars);
   }
}