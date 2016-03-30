<?php
namespace Bitphp;

use \Bitphp\Config;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Cache 
{
  protected static function cacheEnabled($owner) 
  {
    if(true === Config::param("$owner.cache")) return true;
    return false;
  }

  protected static function cacheTime($owner) 
  {
    $cachetime = Config::param("$owner.cache.life");

    if( null === $cachetime || !is_integer($cachetime) ) $cachetime = 300; //senconds
    return $cachetime;
  }

  protected static function generateName($hash, $owner) 
  {
    return "../app/cache/$hash.$owner.lock";
  }

  public static function read($owner, $hash) 
  {
    if(!self::cacheEnabled($owner)) return false;

    $file = self::generateName($hash, $owner);

    if(!file_exists($file)) return false;

    $content = file_get_contents($file);

    if((fileatime($file) + self::cacheTime($owner)) >= time()) return $content;

    unlink($file);
    return false;
  }

  public static function save($owner, $hash, $content) 
  {
    if(!self::cacheEnabled($owner)) return false;
         
    $file = self::generateName($hash, $owner);
    $dir = dirname($file);
        
    if(!is_dir($dir)) mkdir($dir, 0777, true);
    file_put_contents($file, $content);
  }
}