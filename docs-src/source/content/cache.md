Sistema de cache
################

Este sistema es utilizado por el motor de plantillas Medusa y el manejador de vistas, para reducir la carga del servidor y aumentar la velocidad de respuesta. Se usa a través de la clase ``\Bitphp\Cache``

Guardar datos en cache
======================

``\Bitphp\Cache::save(string $owner, string $id, string $content)``

Guarda el contenido ``$content`` en fichero qué se guarda en ``/app/cache/`` nombrado por ``$id``. El tercer parametro ``$owner`` es la entidad qué trata de guardan en cache, lo qué hace el sistema es verificar qué dicha entidad tenga permitido usar el cache, verificando qué ``<owner>.cache`` sea igual a ``true`` en el fichero de configuracion ``/app/config.json``.

Para construir un ``$id``, por poner un ejemplo, Medusa lo hace hasheando con md5 el nombre de la plantilla qué se va a cargar y los parametros qué se le enviaron, para ``$owner`` utiliza ``'medusa'`` y para ``$content`` el contenido de la plantilla ya compilado y procesado. De esta manera si se vuelve a solicitar la misma plantilla con los mismos parametros no se detendra a compilar y procesar de nuevo.

Leer datos del cache
====================

``\Bitphp\Cache::read(string $owner, string $id)``

Lee el contenido previamente guardado con el identificador ``$id``, en caso de no existir en cache, o simplemente no esta habilitado el cache para ``$owner`` retornará ``false``.

Ejemplo
=======

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Cache;

   Route::match('GET /fruit/:name', function($name) {
      $content = Cache::read('fruits', md5($name));

      if($content) {
         echo $content;
         return;
      }

      $content = "Fruta $fruit cacheada a las " . time();

      // en app/config.json debe estar: "fruits.cache" = true
      Cache::save('fruits', md5($fruit), $content);
      echo $content;
   });