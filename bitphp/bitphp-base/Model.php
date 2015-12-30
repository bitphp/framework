<?php

   namespace Bitphp\Base;

   use \Bitphp\Core\Config;

   class Model
   {
      protected $user;
      protected $pass;
      protected $host;

      public function __construct()
      {
         $user = Config::param('db.user');
         $pass = Config::param('db.pass');
         $host = Config::param('db.host');

         $this->host = ($host === null) ? 'localhost': $host;
         $this->user = ($user === null) ? 'root': $user;
         $this->pass = ($pass === null) ? '': $pass;
      }

      protected function alias($name)
      {
         $dbname = Config::param("db.alias.$name");
         
         if(null === $dbname)
            trigger_error("Undefined database alias $name", E_USER_ERROR);

         return $dbname;
      }
   }