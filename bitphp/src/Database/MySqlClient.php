<?php

   namespace Bitphp\Databases;

   use \PDO;

   class MySqlClient extends Model {

      protected $pdo;
      protected $error;
      protected $table;

      public function connect($dbname) {
         $dbname = $this->alias($dbname);
         $connection = 'mysql:host=' . $this->host . ";dbname=$dbname;charset=utf8";
         $this->pdo  = new PDO($connection, $this->user, $this->pass);
      }

      public function execute($query) {
         $query = str_replace('\\', '\\\\', $query);
         $this->statement = $this->pdo->query($query);
         $error = $this->pdo->errorInfo()[2];

         if($error === null)
            return true;

         $this->error = $error;
         return false;
      }

      public function error() {
         return $this->error;
      }

      public function result() {
         if(($error = $this->error())) {
            trigger_error($error);
            return false;
         }
         
         return $this->statement->fetchAll(PDO::FETCH_ASSOC);
      }

      public function table($table) {
         $this->table = $table;
      }

      public function create($item) {
         if($this->table === null)
            trigger_error('Unespecified table name', E_USER_ERROR);

         $keys   = array();
         $values = array();

         foreach ($item as $key => $value) {
            $keys[] = $key;
            $values[] = "'$value'";
         }

         $keys   = implode(',', $keys);
         $values = implode(',', $values);

         return $this->execute("INSERT INTO $this->table ($keys) VALUES ($values)");
      }

      public function find($match='', $fields=null) {
         if($this->table === null)
            trigger_error('Unespecified table name', E_USER_ERROR);

         $fields = $fields === null ? '*' : implode(',', $fields);
         $match  = $match  !== '' ? "where $match" : '';

         return $this->execute("SELECT $fields FROM $this->table $match");
      }

      public function update($item, $match) {
         if($this->table === null)
            trigger_error('Unespecified table name', E_USER_ERROR);

         $values = array();

         foreach ($item as $key => $value) {
            $values[] = "$key='$value'";
         }

         $values = implode(',', $values);
         return $this->execute("UPDATE $this->table SET $values WHERE $match");
      }

      public function delete($match) {
         if($this->table === null)
            trigger_error('Unespecified table name', E_USER_ERROR);

         return $this->execute("DELETE FROM $this->table WHERE $match");
      }
   }