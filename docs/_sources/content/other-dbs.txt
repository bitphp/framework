Otros manejadores
=================

Si deseas conectar a otros manejadores de bases de datos, MongoDB por ejemplo, Bitphp propone la siguiente solución; Crear un modelo *base* extendiendo a la clase ``Bitphp\Database\Model`` y utilizar los parametros de conexión qué esta clase lee de la configuración:

``/app/config.json``

.. code-block:: php

   {
      "db.user": "YourUserHere",
      "db.pass": "Y0urP4ssH3r3",
      "db.host": "localhost"
   }

``/app/models/BaseModel.php``

.. code-block:: php

   <?php

   namespace Models;

   use \Bitphp\Database\Model;

   class BaseModel extends Model {

      protected static function connect() {
         self::loadConfiguration();
         
         $host = self::$host;
         $user = self::$user;
         $pass = self::$pass;

         return new MongoClient("mongodb://$user:$pass@$host");
      }
   }

``/app/models/Users.php``

.. code-block:: php

   <?php

   namespace Models;

   class Users extends BaseModel {
      
      public static function findAll() {
         $mongo = self::connect();
         $users = $mongo
                     ->myDb
                     ->users
                     ->find();

         return $users;
      }
   }

``/app/models/Posts.php``

.. code-block:: php

   <?php

   namespace Models;

   class Posts extends BaseModel {
      
      public static function findAll() {
         $mongo = self::connect();
         $posts = $mongo
                     ->myDb
                     ->posts
                     ->find();

         return $posts;
      }
   }