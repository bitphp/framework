<?php

   namespace Bitphp\Base\Server;

   /**
    * Clase para la conversion de las uri's de las rutas
    * en patrones regulares
    *
    * @author Eduardo B Romero
    */
   class Pattern 
   {
      /**
       * Crea un patrón regular de la uri de la ruta definida
       *
       * @param string $route uri para convertir
       * @return string
       */
      public static function create($route) 
      {
         if(strlen($route) > 1)
          $route = rtrim($route, '\/');

         $search = [
              '/\//'
            , '/\((int|integer)(\s+\$\w+)?\)/'
            , '/\((dbl|double)(\s+\$\w+)?\)/'
            , '/\((str|string)(\s+\$\w+)?\)/'
            , '/\((any|anything)(\s+\$\w+)?\)/'
         ];

         $replace = [
              '\/'
            , '([0-9]+)'
            , '([0-9]+\.[0-9]+)'
            , '(\w+)'
            , '(.*)'
         ];

         return '/^' . preg_replace($search, $replace, $route) . '$/';
      }
   }