<?php

   namespace Bitphp\Core;

   use \Bitphp\Core\Route;
   use \Bitphp\Core\Config;

   /**
    *   Clase para manipular el cache de bitphp.
    *
    *   por ejemplo, los templates se cachean en base a su nombre de archivo y a 
    *   los parametros qué estos reciban, en base a estos 2 se crea un hash que
    *   se usa para identificarlos en los archivos de cache.
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class Cache {

      /** 
       *  Entidad qué solicita el cache, para que dentro
       *  de la configuracion busque parametros de cache
       *  relacionados con esa entidad:
       *
       *  Eg. medusa.cache.time ó db.cache.time
       */
      protected static $agent = 'app';

      /**
       *  Lee el archivo de configuracion de la aplicacion
       *  y solo si el cache para el agente esta en verdadero
       *  retorna verdadero, si este no es indicado retorna falso
       *
       *  @return bool
       */
      protected static function cacheEnabled() {
        if(true === Config::param(self::$agent . '.cache'))
          return true;

        return false;
      }

      /**
       *  Retorna el tiempo de cache en segundos
       *  ya sea de la configuracion o el valor
       *  por defecto
       *
       *  @return integer
       */
      protected static function cacheTime() {
        $cachetime = Config::param(self::$agent . '.cache.life');

        if( null === $cachetime || !is_integer($cachetime) )
          $cachetime = 300; //senconds

        return $cachetime;
      }

      /**
       *   Crea el nombre de un archivo de cache en base a un arreglo.
       *
       *   @param array $dada Parametros que se toman para general el cache
       *                  por ejemplo el nombre de una vista y los parametros
       *                  que esta recibe.
       *   @return string ruta del archivo del cache
       */
      protected static function generateName($data) {
         return Route::basepath() . "/app/cache/$data.lock";
      }

      /**
       *   Lee el contenido de un archivo en cache, si este existe
       *   y no ha sobrepasado el tiempo de vida
       *
       *   @param array $data Parametros a tomer en cuanta para cachear
       *   @return mixed contenido del archivo si extiste y no a expirado
       *              false de lo contrario
       */
      public static function read($data) {
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

      /**
       *   Guarda algun contenido en el cache identificandolo en base a 
       *   un arreglo de datos especifico
       *
       *   @param array $data arreglo qué servira como identificador
       *   @param string $content contenido para guardar en cache
       *   @return void
       */
      public static function save($data, $content) {
         if(!self::cacheEnabled())
            return false;
         
         $file = self::generateName($data);
         
         $dir = dirname($file);
        
         if(!is_dir($dir))
            mkdir($dir, 0777, true);

         file_put_contents($file, $content);
      }

      public static function agent($name) {
        self::$agent = $name;
      }
   }