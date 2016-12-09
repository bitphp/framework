Primeros pasos
##############

La estructura de ficheros
=========================

Si seguiste la guía de instalación al pie de la letra, dentro de la carpeta ``/var/www/bitphp`` debes tener lo siguiente:

.. image:: ../_static/file-tree-1.png

En dónde:

- **app** es el directorio de la aplicación (modelos, controladores, etc.)
- **bitphp** ficheros fuente de Bitphp
- **web** aquí irán ficheros como hojas de estilo, javascripts, imagenes, y el *index.php*
- **autoload.php** es el fichero para la autocarga de clases

Dentro de la carpeta **app** van todos los componentes de la aplicación:

.. image:: ../_static/file-tree-2.png

Hola mundo
==========

En la carpeta ``/app/web/`` se encuentra el archivo ``index.php``, en este se definen las rutas, vamos a editarlo y substituir su contenido por:

.. code-block:: php

   <?php

   require '../autoload.php';

   use \Bitphp\Route;

   Route::match('GET /hello', function() {
      echo "Hola mundo!";
   });

Y al ir la dirección ``http://localhost/hello`` podremos ver:

.. image:: ../_static/hello-world.png

**¡Está todo listo!**