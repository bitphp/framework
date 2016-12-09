Requested url
#############

Url Base
========

``\Bitphp\Url::base()``

Permite conocer la url base del servidor, ``http://localhost``, ``https://example.com``, ``http://localhost/bitphp``, es decir, la dirección del servidor en dónde se encuentre.

Path solicitado
===============

``\Bitphp\Url::path()``

Permite conocer el path de la solicitud, ``http://localhost/foo/bar`` dónde ``/foo/bar`` es el path.

.. note::

   El path se obtiene de $_GET['_url'] y esto es gracias a el rewrite rule de apache o configurando nginx para url's amigables.