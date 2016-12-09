Http
####

- :ref:`request`
   
   - :ref:`request-get`
   - :ref:`request-post`
   - :ref:`request-cookie`
   - :ref:`request-header`
   - :ref:`request-other`

- :ref:`response`

   - :ref:`response-status`
   - :ref:`response-format`

.. _request:

Request
=======

Lee datos (formularios, cookies, headers, etc.) de la petición a través de la clase ``\Bitphp\Http\Request``, desde alguna ruta o controlador. Este tipo de parametros los lees normalmente por medio de las variables ``$_GET``, ``$_POST`` o ``$_COOKIE``, la diferencia es que con Bitphp estas son filtradas para evitar posibles fallos de seguridad.

.. code-block:: php

   <?php
   
   require '../autoload.php';

   use \Bitphp\Http\Request;

   $foo = Request::post('foo'); // $_POST['foo']

.. code-block:: php

   <?php

   namespace Controllers;

   use \Bitphp\Http\Request;

   class MyController {

      public static function foo() {
         $foo = Request::post('foo'); // $_POST['foo']         
      }
   }

.. _request-post:

Parametros post
---------------

``\Bitphp\Http\Request::post(string $index [, bool $filter=true])``

.. code-block:: php

   <?php

      // ...
      $foo = Request::post('foo'); // $_POST['foo']
      // ...

.. _request-get:

Parametros get
--------------

``\Bitphp\Http\Request::get(string $index [, bool $filter=true])``

.. code-block:: php

   <?php

      // ...
      $bar = Request::get('bar'); // $_GET['bar']
      // ...

.. _request-cookie:

Lectura de cookies
------------------

``\Bitphp\Http\Request::cookie(string $index [, bool $filter=true])``

.. code-block:: php

   <?php

      // ...
      $bar = Request::cookie('bar'); // $_COOKIE['bar']
      // ...

.. _request-header:

Lectura de cabeceras Http
-------------------------

``\Bitphp\Http\Request::header(string $index [, bool $filter=true])``

.. code-block:: php

   <?php

      // ...
      $content_type = Request::header('content-type');
      // ...

.. _request-other:

Otros métodos Http de entrada
-----------------------------

``\Bitphp\Http\Request::input(string $index [, bool $filter=true])``

Php no tiene una forma de recibir parametros por medio de otros metodos http (put, patch, delete, etc) como lo hace con *get* y *post*, sin embargo con bitphp, si los parametros qué quieres recibir son enviados por algún otro *método http*, usa ``\Bitphp\Http\Request::input()``:

.. code-block:: php

   <?php

      // ...
      $foo = Request::input('foo');
      // ...

.. note::

   Si deseas qué las variables de entrada no sean filtradas pasa el segundo parametro para ``post()``, ``get()`` y ``input()`` como ``false``:

   .. code-block:: php

      <?php

      // ...
      $foo = Request::post('foo', false);
      // ...

.. _response:

Response
========

Puedes utilizar la clase ``\Bitphp\Http\Response``, principalmente para indicar los estados de respuesta (404, 500, etc.) o enviar tipos de datos especiales (xml o json).

.. code-block:: php

   <?php
   
   require '../autoload.php';

   use \Bitphp\Http\Response;

   Route::ifNotMatch(function() {
      Response::status(404);
      echo "404 Not Found";
   });

.. code-block:: php

   <?php

   namespace Controllers;

   use \Bitphp\Http\Response;

   class ErrorPages {

      public static function notFound() {
         Response::status(404);
         echo "404 Not Found";     
      }
   }

.. _response-status:

Estado de respuesta
-------------------

``\Bitphp\Http\Response::status(int $code)``

Usa el método ``Response::status()``, para setear la cabecera de estado que deseas enviar, ``$code`` es el numero correspondiente al mensaje de estado, los estados disponibles son:

+----------------+-----------------------------------------------+
| Codigo         |   Mensaje de Estado                           |
+================+===============================================+
| 200            | OK                                            |
+----------------+-----------------------------------------------+
| 201            | Created                                       |
+----------------+-----------------------------------------------+
| 202            | Accepted                                      |
+----------------+-----------------------------------------------+
| 204            | No content                                    |
+----------------+-----------------------------------------------+
| 301            | Moved Permanently                             |
+----------------+-----------------------------------------------+
| 302            | Found                                         |
+----------------+-----------------------------------------------+
| 303            | See other                                     |
+----------------+-----------------------------------------------+
| 304            | Not Modified                                  |
+----------------+-----------------------------------------------+
| 400            | Bad Request                                   |
+----------------+-----------------------------------------------+
| 401            | Unauthorized                                  |
+----------------+-----------------------------------------------+
| 403            | Forbidden                                     |
+----------------+-----------------------------------------------+
| 404            | Not Found                                     |
+----------------+-----------------------------------------------+
| 405            | Method Not Allowed                            |
+----------------+-----------------------------------------------+
| 410            | Gone                                          |
+----------------+-----------------------------------------------+
| 415            | Unsupported Media Type                        |
+----------------+-----------------------------------------------+
| 422            | Unprocessable Entity                          |
+----------------+-----------------------------------------------+
| 429            | Too Many Requests                             |
+----------------+-----------------------------------------------+
| 500            | Internal Server Error                         |
+----------------+-----------------------------------------------+

.. _response-format:

Formato de respuesta
--------------------

Las aplicaciónes restful son una tendencia actualmente, es común qué estas API's respondan en formatos Json o Xml.

Responder en formato JSON
+++++++++++++++++++++++++

``\Bitphp\Http\Response::json(mixed $data)``

Para este método ``$data`` puede ser un array, o un string en formato json, las cabeceras necesarias son seteadas automaticamente para qué el cliente sepa que es una respuesta en formato json.

.. code-block:: php

   <?php

      use \Bitphp\Http\Response;

      Response::json(array(
           'foo' => 'bar'
         , 'baz' => 'quux'
      ));

.. code-block:: php

   <?php

      use \Bitphp\Http\Response;

      Response::json('{"foo":"bar","quux":true}');

Responder en formato XML
++++++++++++++++++++++++

``\Bitphp\Http\Response::xml(string $data)``

Para enviar una respuesta del tipo xml ``$data`` debe ser explicitamente una cadena en formato xml.

.. code-block:: php

   <?php

      use \Bitphp\Http\Response;

      Response::json("<foo><bar>quux</bar></foo>");