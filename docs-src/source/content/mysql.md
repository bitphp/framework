Cliente MySql
#############

- :ref:`auto-mysql-conn`
- :ref:`crud`

   - :ref:`select-table`
   - :ref:`create`
   - :ref:`read`
   - :ref:`update`
   - :ref:`delete`

- :ref:`other-queries`
- :ref:`query-errors`

.. _auto-mysql-conn:

Conexión del modelo a MySql
===========================

``\Bitphp\Database\MySqlClient::connect(string $dbname)``

Extiende tu modelo a la clase ``\Bitphp\Database\MySqlClient`` para conectar de manera sencilla a una base de datos MySql:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      public static function find() {
         // connect to "system" db
         self::connect('system');
         // ...
      }
   }

.. _crud:

CRUD
====

.. _select-table:

Seleccionar tabla
-----------------

``\Bitphp\Database\MySqlClient::table(string $tbl_name)``

Elije la tabla en la cual se van a ejecutar las consultas del CRUD:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users'); // El crud trabajará sobre la tabla "users"
      }

      public static function find() {
         self::init();
      }
   }

.. _create:

Create (añadir registros)
-------------------------

``\Bitphp\Database\MySqlClient::create(array $registro)``

Añade un nuevo registro a la tabla seleccionada, ``$regitsro`` es un array en formato ``array('clave' => 'valor')``, para poder ser traducido a ``"INSERT INTO FOO(clave) VALUES ('valor')"``, retorna ``true`` si el registro fue creado y ``false`` en caso de error.

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users'); // El crud trabajará sobre la tabla "users"
      }

      public static function insert($nombre, $edad, $correo) {
         self::init();

         // INSERT INTO users (nombre, edad, correo) VALUES ('$nombre', '$edad', '$correo')
         self::create(array(
              'nombre' => $nombre
            , 'edad'   => $edad
            , 'correo' => $correo
         ));
      }
   }

.. _read:

Read (leer registros)
---------------------

``\Bitphp\Database\MySqlClient::find([, string $match, array $fields])``

Método qué permite leer registros, el parametro opcional ``$match`` sirve para delimitar por medio de condicionales la lectura de los campos y el parametro opcional ``$fields`` delimita los campos qué van a ser leídos, retorna un array con los campos, un array vacio si no hay campos o ``false`` en caso de error.

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users'); // El crud trabajará sobre la tabla "users"
      }

      public static function findAll() {
         self::init();

         // SELECT * FROM users
         return self::find();
      }

      public static function findOne($id) {
         self::init();

         // SELECT * FROM users WHERE id='$id'
         return self::find("id='$id'");
      }

      public static function findAllPersonalInfo() {
         self::init();

         // SELECT name, edad FROM users
         return self::find('', array('name', 'edad'));
      }

      public static function findOnePersonalInfo($id) {
         self::init();

         // SELECT name, edad FROM users WHERE id='$id'
         return self::find("id='$id'", array('name', 'edad'));
      }

      public static function auth($user, $pass) {
         self::init();

         // SELECT * FROM users WHERE (user='$user' OR email='$user') AND pass='$pass'
         $match = "(user='$user' OR email='$user') AND pass='$pass'";
         return self::find($match);
      }
   }

.. _update:

Update (actualizar registros)
-----------------------------

``\Bitphp\Database\MySqlClient::find(string $match, array $item)``

Actualiza el los campos del registro coincidente con la condicional ``$match``, los campos a actualizar son ``$item`` de la forma ``array('campo' => 'nuevo_valor')``, retorna ``true`` si se actualizó el registro o ``false`` si hubo algún error:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users'); // El crud trabajará sobre la tabla "users"
      }

      public static function updateName($id, $new_name) {
         self::init();

         // UPDATE users SET (nombre='$new_name') WHERE id='$id'
         self::update("id='$id'", array(
            'nombre' => $new_name
         ));
      }
   }

.. _delete:

Delete (borrar registros)
-------------------------

``\Bitphp\Database\MySqlClient::delete(string $match)``

Elimina el registro que coincida con la condicional ``$match``, retorna ``true`` si se elimmino el registro o ``false`` si hubo algún error:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users'); // El crud trabajará sobre la tabla "users"
      }

      public static function deleteUser($id) {
         self::init();

         // DELETE FROM users WHERE id='$id'
         self::delete("id='$id'");
      }
   }

.. _other-queries:

Otras consultas
===============

``\Bitphp\Database\MySqlClient::execute(string $query)``

Ejecuta la consulta ``$query`` en la base de datos.

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
      }

      public static function findAll($id) {
         self::init();
         self::execute("SELECT * FROM users");
      }
   }

``\Bitphp\Database\MySqlClient::result()``

Retorna el valor obtenido de una consulta de selección, un ``array`` si la consulta de selección fue correcta o ``false`` en caso de error.

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
      }

      public static function findAll($id) {
         self::init();
         self::execute("SELECT * FROM users");
         return self::result();
      }
   }

.. _query-errors:

Errores de consulta
===================

``\Bitphp\Database\MySqlClient::error()``

Si algúna consulta falla (retorna ``false``), ya sea con el CRUD (``create()``, ``find()``, ``update()``, ``delete()``) o con una consulta directa (``execute()``) puedes mostrar el error y salir:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\MySqlClient;

   class Users extends MySqlClient {
      
      private static function init() {
         self::connect('my_database');
         self::table('users')
      }

      public static function findAll($id) {
         self::init();
         self::execute("SELECT * FROM users");

         if(null !== self::$error) 
            trigger_error(self::$error, E_USER_ERROR);

         return self::result();
      }

      public static function findOne($id) {
         self::init();

         // SELECT * FROM users WHERE id='$id'
         $user = self::find("id='$id'");

         if(!$user)
            trigger_error(self::$error, E_USER_ERROR);

         return $user;
      }

      public static function deleteUser($id) {
         self::init();

         // DELETE FROM users WHERE id='$id'
         $done = self::delete("id='$id'");

         if(!$done)
            trigger_error(self::$error, E_USER_ERROR);
      }
   }