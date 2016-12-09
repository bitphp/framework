Motor de Plantillas Medusa
##########################

- :ref:`template-creation`
- :ref:`template-draw`
- :ref:`template-params`
- :ref:`medusa-lang`

   - :ref:`short-echo`
   - :ref:`short-array`
   - :ref:`control-blocks`
   - :ref:`utilities`
   - :ref:`inheritance`

- :ref:`template-include`

.. _template-creation:

Crear una plantilla
===================

La clase ``\Bitphp\Layout\Medusa`` permite el manejo de plantillas con un lenguaje simple y elegante, para reducir el trabajo al usar php embebido en html. Las plantillas se crean en la carpeta ``/app/views/sources`` y **deben** tener la extensión ``<nombre>.medusa.php``:

``/app/views/sources/hello.medusa.php``

.. code-block:: php

   <h1>Hello world!</h1>

.. _template-draw:

Mostrar una plantilla
=====================

``\Bitphp\Layout\Medusa::draw(string $template[, array $args])``

El método estatico ``Views::draw()`` recibe como primer parametro el nombre de la plantilla **sin extensión** y la muestra (``echo``):

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Medusa;

   Route::match('GET /', function() {
      Medusa::draw('hello'); // muestra app/views/sources/hello.medusa.php
   });

.. _template-params:

Enviar parametros a las plantillas
==================================

``\Bitphp\Layout\Medusa::draw(string $template[, array $args])``

Envia en el segundo parametro (el cual es opcional) un arreglo de la forma ``array('parametro' => 'valor')`` y dentro de la plantilla podrás usar el parametro como una variable ``$parametro``:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Medusa;

   Route::match('GET /:name', function($name) {
      Medusa::draw('hello', array('name' => $name));
   });

``/app/views/sources/hello.medusa.php``

.. code-block:: html

   <h1>Hello {{ $name }}!</h1>

.. _medusa-lang:

El lenguaje Medusa
==================

.. _short-echo:

Short echo
----------

``{{ $string }}``

En lugar de hacer esto ``<div><?php echo $string ?></div>`` al usar plantillas Medusa puedes hacer ``{{ $string }}``:

.. code-block:: html

   <div>Foo: {{ $string }}</div>

.. _short-array:

Short array
-----------

``$array.key``

Puedes acceder a los elementos de un array facilmente con ``$array.key``, equivalente a ``$array['key']``:

.. code-block:: php

   <?php

   //...
      Medusa::draw('profile', array(
         'user' => array(
              'name' => 'Barney'
            , 'age'  => 25
         )
      ));
   //...

.. code-block:: html

   <!-- app/views/sources/profile.view.php -->
   <h1>Hello {{ $user.name }}, you are {{ $user.age }} years old!</h1>

.. note::
   
   No es recursivo, es decir, solo funciona para los primeros elementos, ejemplo, para ``$user['info']['name']`` **NO** se puede hacer ``$user.info.name``.

.. _control-blocks:

Bloques de control
------------------

**Bloque If**

.. code::

   :if <condition>
      <statements>
   :elseif <condition>
      <statements>
   :else
      <statements>
   :endif

Reduce notablemente la sintaxis del bloque if, la condición se plantea justo como se hace tradicionalmente con ``<?php if(<condition>): ?>``

.. code-block:: html

   <div>
      :if $user.age >= 18
         <h1>Eres mayor de edad!</h1>
      :endif
   </div>

.. code-block:: html

   <div>
      :if $user.age >= 18
         <h1>Eres mayor de edad!</h1>
      :else
         <h1>Eres menor de edad!</h1>
      :endif
   </div>

.. code-block:: html

   <div>
      :if $user.age > 18
         <h1>Eres mayor de edad!</h1>
      :elseif $user.age == 18
         <h1>Acabas de convertirte en adulto!</h1>
      :else
         <h1>Eres menor de edad!</h1>
      :endif
   </div>


**Bloque For**

.. code::

   :for expr1; expr2; expr3
      <statements>
   :endfor

.. code-block:: html

   <ul>
      :for $i=1; $i<=10; $i++
         <li>Numero: {{ $i }}</li>
      :endfor
   </ul>

**Bloque Foreach**

.. code::

   :foreach $array as $valor
      <statements>
   :endforeach

.. code::

   :foreach $array as $clave => $valor
      <statements>
   :endforeach

.. code-block:: php

   <?php

   //...
      Medusa::draw('foo', array(
         'fruits' => array(
              'mango'
            , 'sandia'
            , 'zanahoria'
            , 'gato'
         )
      ));
   //...

.. code-block:: html
   
   <!-- app/views/sources/foo.medusa.php -->
   <ul>
      :foreach $fruits as $fruit
         <li>{{ $fruit }}</li>
      :endforeach
   </ul>

.. _utilities:

Utilidades
----------

**Incluir ficheros CSS**

.. code::

   :css fichero

Se traduce como ``<link rel="stylesheet" href="http://example.com/static/css/fichero.css">``. Para ello *fichero* debe ser accesible a través de ``http://example.com/static/css/fichero.css``, ``http://example.com`` cambia automaticamente dependiendo del hostname de tu servidor.

.. code-block:: html

   <html>
      <head>
         :css bootstrap
         :css theme
      </head>
      <body>
         <!-- -->
      </body>
   </html>

**Incluir ficheros JS**

.. code::

   :js fichero

Se traduce como ``<script src="http://example.com/static/js/fichero.js">``. Para ello *fichero* debe ser accesible a través de ``http://example.com/static/js/fichero.js``, ``http://example.com`` cambia automaticamente dependiendo del hostname de tu servidor.

.. code-block:: html

   <html>
      <head>
         :js jquery
         :js app
      </head>
      <body>
         <!-- -->
      </body>
   </html>

**Url base**

.. code::

   @baseurl

Es un string con la url base en la qué se encuentra funcionando bitphp, por ejemplo ``http://example.com``, ``https://my.app.com`` o ``http://example.com/bitphp``, etc.

.. code-block:: html
   
   <a href="{{ @baseurl }}/user/profile">Link a perfil de usuario</a>

.. _inheritance:

Herencia
--------

Crea una plantilla *base* a partir de la cual puedas crear plantillas qué hereden partes qué tienen en común.

**Definición de bloques**

.. code::

   :block <name>
      <content>
   :endblock

La plantilla base contendrá bloques qué podrán ser sobrescritos por las plantillas qué la hereden:

.. code-block:: html

   <!-- app/views/sources/main.medusa.php -->
   <html>
      <head>
         <title>Foo bar</title>
      </head>
      <body>
         
         :block body
            <!-- default content -->
         :endblock

         :block footer
            <p>Esto aparece si la plantilla que hereda no indica este bloque.</p>
         :endblock

      </body>
   </html>

**Extender a la plantilla principal**

.. code::

   :extends <template-name>

La plantilla puede heredar a la principal escribiendo en la primer linea ``:extends <name>``, y debe crear los bloques qué se van a sobrescribir de la plantilla principal.

Por ejemplo, para extender a la plantilla ``app/views/sources/main.medusa.php``:

.. code-block:: html

   :extends main

   :block body
      <p>Contenido para el bloque body!</p>
   :endblock

Entonces a partir de esta herencia la plantilla resultante se vería así:

.. code-block:: html

   <html>
      <head>
         <title>Foo bar</title>
      </head>
      <body>
         <p>Contenido para el bloque body!</p>
         <p>Esto aparece si la plantilla que hereda no indica este bloque.</p>
      </body>
   </html>

Puede conservar el contenido del bloque de la pantilla principal indicandolo con ``:parent`` al inicio o final del bloque:

.. code-block:: html

   :extends main

   :block body
      :parent
      <p>Contenido para el bloque body!</p>
   :endblock

**Ejemplo**

``app/views/sources/main.medusa.php``

.. code-block:: html

   <!-- app/views/sources/main.medusa.php -->
   <html>
      <head>
         <title>My Great Page</title>
         :css foo-theme
         :js my-script
      </head>
      <body>
         <navbar>
            <!-- foo -->
         </navbar>

         :block body
            <!-- default content -->
         :endblock

         <footer>
            <!-- foo -->
         </footer>
      </body>
   </html>

``app/views/sources/profile.medusa.php``

.. code-block:: html

   :extends main

   :block body
      <h1>Hello {{ $user.name }}</h1>
      <h2>Hoobies:</h2>
      <ul>
         :foreach $user.hoobies as $hooby
            <li>{{ $hooby }}</li>
         :endforeach
      </ul>
   :endblock

``app/views/sources/fruits.medusa.php``

.. code-block:: html

   :extends main

   :block body
      <h1>Fruits:</h1>
      <ul>
         :foreach $fruits as $fruit
            <li>{{ $fruit }}</li>
         :endforeach
      </ul>
   :endblock

Ambas plantillas heredan a main, por lo tanto tendrán su estructura basica, header, los css incluidos, etc. ya qué solo sobrescriben el bloque *body*. Para usarlas:

.. code-block:: php

   <?php

   require '../autoload.php'

   use \Bitphp\Route;
   use \Bitphp\Layout\Medusa;

   Route::match('GET /user/:id/profile', function($id) {
      $user = \Models\Users::find($id) // just an example

      // app/views/sources/profile.medusa.php
      Medusa::draw('profile', array('user' => $user));
   });

   Route::match('GET /fruits', function() {
      // app/views/sources/fruits.medusa.php
      Medusa::draw('fruits', array(
         'fruits' => array(
              'perro'
            , 'gato'
            , 'caballo'
         )
      ));
   });

.. _template-include:

Incluir plantillas dentro de otras
==================================

``:include <template-name>``

Este método permite insertar el contenido de una plantilla en otra, y así poder reutilizar código.

``app/views/sources/items/navbar.medusa.php``

.. code-block:: php

   <div class="navbar">
      <span class="navbar-title">{{ $user.name }}</span>
   </div>

``app/views/sources/profile.medusa.php``

.. code-block:: php

   <html>
      <head>
         <title>{{ $user.name }} profile</title>
      </head>
      <body>
         :include items/navbar
         <div class="global-container">
            About you: <p>{{ $user.about }}</p>
         </div>
      </body>
   </html>

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;
   use \Bitphp\Layout\Medusa;

   Route::match('GET /user/:id', function($user_id) {
      $user = \Models\Users::find($user_id); // just an example
      Medusa::draw('profile', array('user' => $user);
   });

.. note::

   Las plantillas incluidas tendrán acceso a los parametros qué fueron pasados a la vista principal.

Cache de plantillas
===================

Las plantillas pueden usar un sistema de cache y ayudar a reducir la carga del servidor, para activarlo, en el archivo de configuración de la aplicación ``/app/config.json``, indica el parametro ``medusa.cache`` como ``true``, para desactivarlo colocalo como ``false``:

``/app/config.json``

.. code-block:: json

   {
      "medusa.cache": true
   }

Puedes indicar el tiempo de vida de los ficheros de cache indicando los **segundos** antes de que el fichero de cache sea desechado, con el parametro de configuración ``medusa.cache.life``:

.. code-block:: json

   {
      "medusa.cache": true,
      "medusa.cache.life": 300
   }

Debug de plantillas
===================

``\Bitphp\Layout\Medusa::debug(string $view)``

Compila la vista y la imprime con escapandola con ``htmlentities()`` para poder visualizarla como texto plano.