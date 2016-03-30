<?php
namespace Bitphp\Database;

use \Bitphp\Config;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Model
{
   protected static $user;
   protected static $pass;
   protected static $host;

   protected static function loadConfiguration()
   {
      $user = Config::param('db.user');
      $pass = Config::param('db.pass');
      $host = Config::param('db.host');

      self::$host = ($host === null) ? 'localhost': $host;
      self::$user = ($user === null) ? 'root': $user;
      self::$pass = ($pass === null) ? '': $pass;
   }

   protected static function alias($name)
   {
      $dbname = Config::param("db.alias.$name");
      if(null === $dbname) return $name;
      return $dbname;
   }
}