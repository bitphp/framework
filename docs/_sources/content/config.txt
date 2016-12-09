Acceder la configuración
########################

La clase ``\Bitphp\Config`` permite la lectura de los parametros de configuración definidos en el archivo ``/app/config.json``.

Lectura de un parametro
=======================

``\Bitphp\Config::param(string $param)``

Devuelve el valor del parametro ``$param`` en caso de qué este no exista en la configuración devuelve ``null``.

Lectura de todos los parametros
===============================

``\Bitphp\Config::all()``

Devuelve un arreglo con todos los parametros de configuración.

Ejemplo
=======

``/app/config.json``

.. code-block:: json

   {
      "app.name": "My Great App"
   }

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Config;

   Route::match('GET /config', function() {
      $name   = Config::param('app.name'); // My Great App
      $author = Config::param('app.author'); // null
   });