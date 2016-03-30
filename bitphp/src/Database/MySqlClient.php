<?php
namespace Bitphp\Database;

use \PDO;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class MySqlClient extends Model 
{
   private static $stmt;
   private static $table;
   protected static $error;
   protected static $pdo;

   public static function connect($dbname) 
   {
      self::loadConfiguration();

      if(self::$pdo !== null) return;

      $dbname = self::alias($dbname);
      $connection = 'mysql:host=' . self::$host . ";dbname=$dbname;charset=utf8";
      self::$pdo  = new PDO($connection, self::$user, self::$pass);
   }

   public static function execute($query) 
   {
      $query = str_replace('\\', '\\\\', $query);
      self::$stmt  = self::$pdo->query($query);
      self::$error = self::$pdo->errorInfo()[2];
   }

   public static function result() 
   {
      if(self::$error) return false;
      return self::$stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   public static function table($table) 
   {
      self::$table = $table;
   }

   public static function create($item) 
   {
      if(self::$table === null)
         trigger_error('Unespecified table name', E_USER_ERROR);

      $keys   = array();
      $values = array();

      foreach ($item as $key => $value) {
         $keys[] = $key;
         $values[] = "'$value'";
      }

      $keys   = implode(',', $keys);
      $values = implode(',', $values);

      self::execute('INSERT INTO ' . self::$table . "($keys) VALUES ($values)");
      return self::$error === null ? true : false;
   }

   public static function find($match='', $fields=null) 
   {
      if(self::$table === null)
         trigger_error('Unespecified table name', E_USER_ERROR);

      $fields = $fields === null ? '*' : implode(',', $fields);
      $match  = $match  !== '' ? "WHERE $match" : '';

      self::execute("SELECT $fields FROM " . self::$table . " $match");
      return self::result();
   }

   public static function update($match, $item) 
   {
      if(self::$table === null)
         trigger_error('Unespecified table name', E_USER_ERROR);

      $values = array();

      foreach ($item as $key => $value) {
         $values[] = "$key='$value'";
      }

      $values = implode(',', $values);
      self::execute('UPDATE ' . self::$table . " SET $values WHERE $match");
      return self::$error === null ? true : false;
   }

   public function delete($match) 
   {
      if(self::$table === null)
         trigger_error('Unespecified table name', E_USER_ERROR);

      self::execute('DELETE FROM ' . self::$table . " WHERE $match");
      return self::$error === null ? true : false;
   }
}