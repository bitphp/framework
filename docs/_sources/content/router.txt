Enrutamiento
############

- :ref:`basics`
- :ref:`methods`
- :ref:`parametters`
- :ref:`micro-validating`
- :ref:`regular-validating`
- :ref:`route-grouping`
- :ref:`default-route`

.. _basics:

Enrutamiento básico
===================

Las rutas son definidas dentro del archivo ``html/index.php``, utilizando el método estatico ``match($route, $callback)`` de la clase ``\Bitphp\Route``, este método provee de una manera sencilla de organizar y procesar las peticiones:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /hello', function() {
      echo "Hello world!";
   });

.. _methods:

Métodos Http para enrutamiento
==============================

El parametro ``$route`` es un string compuesto por ``"<HTTP-METHOD> /URL/PATH"``, es decir, el método Http y el path de la url al los cuales responde la ruta.

Es necesario indicar el método http al qué responde la ruta, ya qué pueden existir 2 rutas qué buscan el mismo path pero para diferente método Http:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /users', function() {
      // GET example.com/users
   });

   Route::match('POST /users', function() {
      // POST example.com/users
   });

.. note::

   Si no estas familiarizado con los métodos Http puedes `leer más en este enlace. <http://trevinca.ei.uvigo.es/~txapi/espanol/proyecto/superior/memoria/node46.html>`_

.. warning::

   La definición de la ruta, método y path, **deben ir separados por un espacio**, el método Http no es *case sensitive* por lo qué puede ir en mayúsculas o minúsculas.

.. _parametters:

Rutas con parametros
====================

Si necesitas capturar datos de los fragmentos de la URI solo define dichos fragmentos como parametros en la ruta:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /profile/$user_id', function($user_id) {
      // ...
   });

   Route::match('GET /profile/$user_id/post/$post_id', function($user_id, $post_id) {
      // ...
   });

.. _micro-validating:

Micro validación de parametros
------------------------------

Puedes hacer qué el parametro definido solo acepte cierto tipo de valores, caracteres numericos, caracteres *palabra*, o cualquierer caracter.

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /profile/$user_id', function($user_id) {
      // $user_id, acepta cualquier tipo de caracteres
   });

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /profile/str$user_id', function($user_id) {
      // $user_id, acepta caracteres "palabra" (\w+)
   });

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /profile/int$user_id', function($user_id) {
      // $user_id, acepta solo caracteres numericos ([0-9]+)
   });

.. _regular-validating:

Validación de parametros con patrones regulares
-----------------------------------------------

Puedes usar directamente patrones regulares para la validación de los parametros:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /email/(\w+\@\w+\.\w+)', function($email) {
      // ...
   });

.. _route-grouping:

Agrupamiento de rutas
=====================

Esto permite agrupar un conjunto de rutas qué comparten un prefijo común, por ej. ``/api/v1/user``, ``/api/v1/post``, ``/api/v1/profile``, con la finalidad de tener un mejor orden. 

Se usa ``Route::group($prefix, $callback)`` para la agrupación, ``$prefix`` sería el prefijo común de las rutas y ``$callback`` una función anonima para definir las rutas agrupadas:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::group('/api/v1', function() {

      Route::match('GET /', function(){
         // get example.com/api/v1
      });

      Route::match('GET /user', function(){
         // get example.com/api/v1/user
      });

      Route::match('POST /', function(){
         // post example.com/api/v1
      });

      Route::match('POST /user', function(){
         // post example.com/api/v1/user
      });
   });

También se puede realizar una sub-agrupación de rutas:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::group('/api', function() {

      Route::group('/v1', function() {

         Route::match('GET /user', function(){
            // get example.com/api/v1/user
         });

         Route::match('POST /user', function(){
            // post example.com/api/v1/user
         });
      });

      Route::group('/v2', function() {
         
         Route::match('GET /user', function(){
            // get example.com/api/v2/user
         });

         Route::match('POST /user', function(){
            // post example.com/api/v2/user
         });
      });
   });

.. _default-route:

Ruta por defecto
================

Se puede ejecutar un ``$callback`` para cuando la ruta solicitada no coincide con ninguna de las rutas definidas, con el método ``Route::ifNotMatch($callback)``:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::ifNotMatch(function($requested_path) {
      echo "404: Element $requested_path not found :(";
   });