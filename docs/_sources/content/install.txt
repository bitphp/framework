Instalación
###########

Requisitos
==========

- Servidor web Apache o Nginx
- PHP 5.4 o superior
- Git y/o Composer (para la instalación)

Configuración del servidor
==========================

Primero hay qué crear una carpeta destinada a trabajar con bitphp, posteriormente configuraremos el servidor web para que sirva dicha carpeta. En nuestro caso estamos usando *Debian Jessie*, por lo tanto crearemos una carpeta en ``/var/www/bitphp``, en la cual vamos a instalar Bitphp.

Nginx
-----

- Hay que lograr que ``nginx`` ejecute los archivos php, si aún no lo has hecho, aquí vamos; hay qué instalar el paquete *php5-fpm* con ``sudo apt-get install php5-fpm``.

- Ahora hay qué hacer un pequeño cambio en la configuración de *PHP*, hay qué abrir el *php.ini*:
   
  .. code::
    
     sudo nano /etc/php5/fpm/php.ini

  Encuentra la linea *cgi.fix_pathinfo=1*, y cambia el 1 por 0:

  .. code::

     cgi.fix_pathinfo=0

  Ahora hay que hacer un pequeño cambio en la configuracion en el *php5-fpm*:

  .. code::

     sudo nano /etc/php5/fpm/pool.d/www.conf

  Encuentra la linea *listen = 127.0.0.1:9000*, y cambia el *127.0.0.1:9000* por */var/run/php5-fpm.sock*

  .. code::

     listen = /var/run/php5-fpm.sock

  Reinicia el *php5-fpm*:

  .. code::

     sudo service php5-fpm restart

- Hay que abrir el archivo para configurar el virtual host:

  .. code::

     sudo nano /etc/nginx/sites-available/default

  Y configuramos el servidor para que, una vez instalado, Bitphp pueda funcionar correctamente:

  .. code::

     server {

        listen   80;
        server_name localhost;
        root /var/www/bitphp/web;
        index index.html index.htm index.php;

        location / {
           try_files $uri $uri/ /index.php?_url=$uri&$args;
        }

        location ~ \.php {
           fastcgi_pass unix:/var/run/php5-fpm.sock;
           fastcgi_index /index.php;
           include fastcgi_params;
           fastcgi_split_path_info       ^(.+\.php)(/.+)$;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }

        location ~ /\.ht {
           deny all;
        }
     }

Y reiniciamos nginx:

.. code::

   sudo service nginx restart

Apache
------

Una vez instalado *Apache* y que esté funcionando *PHP* solo hay un par de configuraciónes por hacer, primero vamos a editar el fichero de configuración del sitio:

.. code::

   sudo nano /etc/apache2/sites-available/default.conf

Y dejamos la configuración de dicho archivo como:

.. code::
   
   <VirtualHost *:80>

      ServerName localhost
      ServerAdmin webmaster@localhost.com
      DocumentRoot /var/www/bitphp/web
      ErrorLog ${APACHE_LOG_DIR}/error.log
      CustomLog ${APACHE_LOG_DIR}/access.log combined

   </VirtualHost>

Ahora hay qué activar el MOD_REWRITE para que el sistema de rutas de Bitphp funcione de manera correcta, para ello ejecutamos:

.. code::

   sudo a2enmod rewrite

A continuación editas el archivo ``/etc/apache2/apache2.conf`` y buscas las líneas **AllowOverride None** y las cambias por **AllowOverride All**

Reiniciamos apache:

.. code::

   sudo service apache2 restart

Instalación vía composer
========================

Hay qué recordar que creamos la carpeta ``/var/www/bitphp``, en esta instalaremos Bitphp vía composer con:

.. code::

   composer create-project bitphp/framework /var/www/bitphp --keep-vcs

Instalación vía Git
===================

Simplemente clonamos el repositorio en la carpeta ``/var/www/bitphp``, con el comando:

.. code::

   git clone https://github.com/bitphp/framework.git /var/www/bitphp

Comprobación de la instalación
==============================

No importa si instalamos vía *composer* o con *git*, sobre *apache* o sobre *nginx*, ahora, al ir desde el navegador a la dorección ``http://localhost``, podremos ver a Bitphp funcionando:

.. image:: ../_static/install-ready.png

Otras configuraciones
=====================

Si en algún futuro no muy lejano se debe usar el sistema de cache de Bitphp deberás dar permisos de escritura a la carpeta ``/var/www/bitphp/app/cache``:

.. code::

   sudo chmod -R 777 /var/www/bitphp/app/cache