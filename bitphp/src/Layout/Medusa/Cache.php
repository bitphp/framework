<?php

   namespace Bitphp\Layout\Medusa;

   use \Bitphp\Config;

   class Cache 
   {
      protected static function cacheEnabled() 
      {
        if(true === Config::param('medusa.cache'))
          return true;

        return false;
      }

      protected static function cacheTime() 
      {
        $cachetime = Config::param('medusa.cache.life');

        if( null === $cachetime || !is_integer($cachetime) )
          $cachetime = 300; //senconds

        return $cachetime;
      }

      protected static function generateName($data) 
      {
         return "../app/cache/$data.lock";
      }

      public static function read($data) 
      {
         if(!self::cacheEnabled())
            return false;

         $file = self::generateName($data);

         if(!file_exists($file))
            return false;

         $content = file_get_contents($file);

         if((fileatime($file) + self::cacheTime()) >= time())
            return $content;

         unlink($file);
         return false;
      }

      public static function save($data, $content) 
      {
         if(!self::cacheEnabled())
            return false;
         
         $file = self::generateName($data);
         
         $dir = dirname($file);
        
         if(!is_dir($dir))
            mkdir($dir, 0777, true);

         file_put_contents($file, $content);
      }
   }