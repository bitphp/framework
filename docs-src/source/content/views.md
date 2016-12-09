Vistas
######

- :ref:`view-creation`
- :ref:`draw-view`
- :ref:`view-params`
- :ref:`view-include`
- :ref:`view-cache`

.. _view-creation:

Crear una vista
===============

La clase ``\Bitphp\Layout\Views`` permite el uso de vistas simples, sin ningún tipo de lenguaje de plantillas. Las plantillas se guardan en la carpeta ``/app/views/sources`` y **deben** tener una extensión ``<nombre>.view.php``.

``/app/views/sources/hello.view.php``

.. code-block:: php

   <h1>Hello world!</h1>

.. _draw-view:

Mostrar una vista
=================

``\Bitphp\Layout\Views::draw(string $view[, array $args])``

El método estatico ``Views::draw()`` recibe como primer parametro el nombre de la vista **sin extensión** y la muestra (``echo``):

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Views;

   Route::match('GET /', function() {
      Views::draw('hello'); // muestra app/views/sources/hello.view.php
   });

.. _view-params:

Enviar parametros a las vistas
==============================

``\Bitphp\Layout\Views::draw(string $view[, array $args])``

Envia en el segundo parametro (el cual es opcional) un arreglo de la forma ``array('parametro' => 'valor')`` y dentro de la vista podrás usar el parametro como una variable ``$parametro``:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Views;

   Route::match('GET /:name', function($name) {
      Views::draw('hello', array('name' => $name));
   });

``/app/views/sources/hello.view.php``

.. code-block:: php

   <h1>Hello <?php echo $name ?>!</h1>

Puedes pasar cualquier tipo de variable, arreglos por ejemplo:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Views;

   Route::match('GET /', function() {
      Views::draw('profile', array(
         'user' => array(
              'name' => 'barney'
            , 'age'  => 25
            , 'hoobies' => array(
                 'skateboard'
               , 'listen to music'
               , 'watch to xvideos'
            )
         )
      ));
   });

``/app/views/sources/profile.view.php``

.. code-block:: php

   <h1>User: <?php echo $user['name'] ?></h1>
   <h2>Age: <?php echo $user['age'] ?> years old</h2>
   <h2>Hoobies:</h2>
   <ul>
      <?php foreach($user['hoobies'] as $hooby): ?>
         <li><?php echo $hooby ?></li>
      <?php endforeach ?>
   </ul>

.. _view-include:

Incluir vistas dentro de otras vistas
=====================================

``\Bitphp\Layout\Views::view_include(string $view)``

Este método permite insertar el contenido de una vista en otra, y así poder reutilizar código. Podrás utilizarlo dentro de las vistas como ``self::include($view)``:

``app/views/sources/items/navbar.view.php``

.. code-block:: php

   <div class="navbar">
      <span class="navbar-title"><?php echo $user['name'] ?></span>
   </div>

``app/views/sources/profile.view.php``

.. code-block:: php

   <html>
      <head>
         <title><?php echo $user['name'] ?> profile</title>
      </head>
      <body>
         <?php self::view_include('items/navbar') ?>
         <div class="global-container">
            About you: <p><?php echo $user['about'] ?></p>
         </div>
      </body>
   </html>

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Views;

   Route::match('GET /user/:id', function($user_id) {
      $user = \Models\Users::find($user_id);
      Views::draw('profile', array('user' => $user);
   });

.. note::

   Las vistas incluidas tendrán acceso a los parametros qué fueron pasados a la vista principal.

.. _view-cache:

Cache de vistas
===============

Las vistas pueden usar un sistema de cache y ayudar a reducir la carga del servidor, para activarlo, en el archivo de configuración de la aplicación ``/app/config.json``, indica el parametro ``views.cache`` como ``true``, para desactivarlo colocalo como ``false``:

``/app/config.json``

.. code-block:: json

   {
      "views.cache": true
   }

Puedes indicar el tiempo de vida de los ficheros de cache indicando los **segundos** antes de que el fichero de cache sea desechado, con el parametro de configuración ``view.cache.life``:

.. code-block:: json

   {
      "views.cache": true,
      "views.cache.life": 300
   }