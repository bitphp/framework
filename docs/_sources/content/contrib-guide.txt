Guia de contribución
####################

Filosófias
==========

Puedes ayudar al código fuente de Bitphp mejorando algoritmos o agregando funciones. El código de Bitphp cumple con ciertas reglas o filosófias:

- El código se debe manter simple y pequeño, por ello, todos los ficheros de Bitphp (de el núcleo), en conjunto, no deben exceder los 50 Kb de tamaño. 

- Los ficheros por si solos no deben exceder las 99 lineas de código, evitemos códigos gigantescos dificiles de leer. Si por alguna razón se excede este limite trata de rapartir funcionalidades entre varios archivos, como es el caso del motor de plantillas Medusa, cuyas funciones están repartidas en varios archivos.

Estándares de codificación
==========================

En cuanto a estilo de programación los ficheros siguen un estandar para qué sean faciles de leer. Las clases se escriben así:

.. code-block:: php

   <?php

   namespace Foo\Bar;

   use \Quux;
   use \Quuux;

   ClassName
   {
      public function baz()
      {
         /...
      }
   }

Las sentencias de control:

.. code-block:: php

   <?php

   if($foo)
   {
      // ...
   }
      else
   {
      // ...
   }

Se trata de usar lo más qué se pueda un if de una sola linea, o si puedes utiliza el operador ternario:

.. code-block:: php

   <?php

   function foo($bar)
   {
      if($bar === 'something') return true;
      // ...
   }

Creemos qué incluso los detalles pueden hacer la diferencia, por eso el código debe estar 100% optimizado. Puedes seguir algunos de estos consejos de optimización:

- **Variables:** No declares variables que no se vayan a usar ya que ocupan memoria. Utiliza constantes para aquellos valores que serán fijos a lo largo de la ejecución. A la hora de declarar variables siempre es preferible usar variables estáticas. Intenta evitar el uso de variables globales.
- **Unset:** Aunque PHP dispone de un Garbage Collector (liberador de memoria) no esta de mas usar la función unset para eliminar variables y aumentar la memoria disponible, sobretodo cuando se usan arrays o variables extensas en servidores limitados.
- **Comparaciones:** Los switch / case usan mas ciclos de procesador que los if / else, por lo tanto usa estos últimos siempre que puedas.
- **Bucles:** Un bucle for es mas lento que un while, que a su vez es mas lento que un do..while. Debes evitar que la condición de parada de tus bucles sea una función, mejor una variable donde se guarde el valor de la función previamente. Revisa tus bucles y elimina aquellos que realmente no son necesarios.
- **Comillas en PHP:** Usa siempre que puedas las comillas simples ya que a diferencia de las dobles, que interpolan los valores de las variables, estas solo interpretan literales, con la consiguiente mejora de procesamiento. Además debes evitar el uso del símbolo del dolar sin escapar (\$) entre comillas dobles ya que ralentiza el código enormemente.
- **Comprobar existencia de variables:** Cuando se necesite verificar la existencia de variables usaremos isset() antes que empty() o is_array(), ya que la primera es la mas eficiente.

Modificar o agregar ficheros al núcleo
======================================

Todos los componentes de Bitphp siguen el estandar ``PSR-4`` para la autocarga de clases. La espacio de nombres base de los componentes de Bitphp es ``\Bitphp`` y este apunta al directorio ``/bitphp/src``.

Si el componente es de ambito general va en ``/bitphp/src/Component.php``, entonces su espacio de nombres es ``\Bitphp``. Si el componente es de ambito especifico, bases de datos por ejemplo, va en ``/bitphp/src/Database/Component.php`` y su espacio de nombres sería ``\Bitphp\Database``.

Crear módulos para Bitphp
=========================

Es simplemente crear una libreria que haga algo útil y qué aproveche las herramientas del núcleo de Bitphp y qué en general trabaje bien con el framework.

Es ideal qué si vas a crear un módulo para bitphp siga el estandar ``PSR-4``, lo subes a github y lo registras en ``packagist`` para qué pueda ser instalado vía composer, te puedes poner en contacto (``bitphp@root404.com``) para registrar tu módulo y ayudar a difundirlo.