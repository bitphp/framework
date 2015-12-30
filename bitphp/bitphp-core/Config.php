<?php 

   namespace Bitphp\Core;

   /**
    *   Proporciona los metodos para leer el archivo de configuracion de
    *   la aplicacion
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class Config {

      protected static $params = array();

      /**
       *   verifica y carga el archivo
       *   de configuracion de la aplicacion
       *
       *   @param string $file Ruta al archivo de configuracion
       *   @param bool $merge indica si se debe mezclar los parametros para cuando se cargan varios archivos
       *   @param bool $environment indica si se debe tomar en cuenta el ambiente de desarrollo
       *   @return void
       */
      public static function load($file, $merge=true) {

         $file = Route::basepath() . "/app/config/$file.json";

         if(file_exists($file)) {
            $content = file_get_contents($file);
            $params = json_decode($content, true);

            if($merge) {

              foreach ($params as $param => $value) {
                self::$params[$param] = $value;
              }

              return;
            }

            self::$params = $params;
         }
      }

      /**
       *   Lee un parametro de configuracion
       *
       *   @param string $index Nombre del parametro de configuracion a leer
       *   @return mixed null si no existe el parametro de configuracion o su valor
       *              en caso de que este exista
       */
      public static function param($index) {
         return isset(self::$params[$index]) ? self::$params[$index] : null;
      }

      /**
       *   Setea un parametro de configuración en tiempo de ejecucion
       *
       *   @param string $index Nombre del parametro de configuracion
       *   @param string $value Valor del parametro de configuración
       *   @return void
       */
      public static function set($index, $value) {
         self::$params[$index] = $value;
      }

      /**
       *   Retorna todos los parametros del archivo de configuracion leidos
       *
       *   @return array
       */
      public static function all() {
         return self::$params;
      }
   }