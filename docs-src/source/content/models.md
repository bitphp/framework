Modelos
#######

- :ref:`model-creation`
- :ref:`model-connection`
- :ref:`model-use`
- :ref:`db-aliases`

.. _model-creation:

Creación de un modelo
=====================

Los modelos se crean en la carpeta ``app/models/``, **deben** ser nombrados en *uppercase* y deben contener una clase con el mismo nombre del archivo perteneciente el espacio de nombres ``\Models``, ej. para el modelo ``app/models/Users.php``:

.. code-block:: php

   <?php

   namespace Models;

   class Users {
      // ...
   }

.. _model-connection:

Parametros de conexión
======================

Se pueden definir los parametros de conexión, usuario, contraseña y host, para usar en todos los modelos desde el archivo de configuración ``app/config.json``:

.. code-block:: json

   {
      "db.user": "your_us3r",
      "db.pass": "your_p4ssw0rd",
      "db.host": "localhost"
   }

Y para utilizarlos simplemente extiende tu modelo a partir de la clase ``\Bitphp\Database\Model``, con esto el modelo hereda el método estatico ``self::loadConfiguration()`` qué seteará las propiedades ``self::$user``, ``self::$pass`` y ``self::$host`` con los valores definidos en la configuración:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\Model;

   class Users extends Model {

      protected $pdo;

      protected static function connect() {
         self::loadConfiguration();
         
         // self::$user == your_us3r
         // self::$pass == your_p4ssw0rd
         // self::$host == connection_host

         // self::$pdo = new PDO(...); conectar via PDO
      }

      public static function find($id) {
         // ... consulta a través del objeto PDO self::$pdo
      }
   }

.. _model-use:

Como usar los modelos
=====================

Desde el controlador o una ruta usa el modelo a través de la clase ``\Models\MyModel``, la auto-carga de Bitphp se encargará de incluir el archivo del modelo.

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /user/$user_id', function($user_id) {
      $user = \Models\Users::find($user_id);
   });

Es por eso qué recomendamos qué tus modelos sean clases con metodos estaticos, para qué sean más comodos de manejar desde los controladores, y de hecho la clase ``\Bitphp\Database\Model`` esta diseñada para funcionar en clases estaticas.

.. _db-aliases:

Conectar usando un alias
========================

Puedes definir un *alias* para un nombre de una base de datos y que los modelos se conecten a ese alias, y en el momento de que el alias *apunte* a una base de datos diferente todos los modelos qué se conectan a ese alias se conectarán a la base de datos *apuntada*.

Los alias se definien en el archivo de configuración ``/app/config.json`` de la forma ``db.alias.<nombre>``, ejemplo:

.. code-block:: json

   {
      "db.alias.great_db": "local_great_db"
   }

Usar el alias desde el modelo
-----------------------------

``\Bitphp\Database\Model::alias(string $alias_name)``

Usa el método heredado ``alias()`` el cual recibe el nombre del alias y retorna la base de datos a la qué apunta, si no esta definido en la configuración retorna el mismo valor pasado:

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\Model;

   class Users extends Model {

      protected $pdo;

      protected static function connect() {
         self::loadConfiguration();
         
         $dbname = self::alias('great_db'); // apunta a local_great_db

         // self::$user == your_us3r
         // self::$pass == your_p4ssw0rd
         // self::$host == connection_host

         // self::$pdo = new PDO(...); conectar via PDO
      }

      public static function find($id) {
         // ... consulta a través del objeto PDO self::$pdo
      }
   }