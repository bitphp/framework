Controladores
#############

- :ref:`linking-route`
- :ref:`definition`
- :ref:`route-params`

.. _linking-route:

Enlazar ruta
============

En lugar de una función (callback) puedes enlazar un controlador a una ruta. ``Route::match($route, $controller)``, en donde ``$route`` es la ruta para enlazar, ver `enrutamiento <router.html>`_, y ``$controller`` es una cadena con el formato ``controlador@función``:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /user/logout', 'user@logout');
   Route::match('POST /user/login', 'user@login');

.. _definition:

Definición de un controlador
============================

Los controladores se crean en la carpeta ``app/controllers``, el controlador **debe** ser nombrado en *uppercase*, ej. ``app/controllers/User.php``, **debe** contener una clase con el mismo nombre del archivo y **debe** pertenecer al espacio de nombres ``\Controllers``, y sus métodos **deben** ser estaticos y de acceso público:

.. code-block:: php

   <?php // app/controllers/User.php

   namespace Controllers;

   class User {

      public static function logout() {
         // ...
      }

      public static function login() {
         // ...
      }
   }

Puedes usar los controladores directamente a través de la clase ``\Controllers\NombreDelControlador`` y la auto-carga de Bitphp se encargará de incluir el archivo del controlador:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /user/logout', function() {
      \Controllers\User::login();
   });

.. _route-params:

Parametros de la ruta
=====================

El ``$callback`` tradicional de la ruta recibe los parametros solicitados a través de parametros en la función, para la función del controlador qué se llama es exactamente lo mismo:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /user/$user_id/photo/$photo_id', 'user@photo');

.. code-block:: php

   <?php

   namespace Controllers;

   class User {

      public static function photo($user_id, $photo_id) {
         // ...
      }
   }