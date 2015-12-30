<?php

   namespace Bitphp\Base;

   use \Bitphp\Core\Mvc;
   use \Bitphp\Core\Event;
   use \Bitphp\Core\Watchdog;
   use \Bitphp\Http\Request;
   use \Bitphp\Base\Server\Pattern;

   class Server
   {
      /** Rutas registradas */
      protected $routes = array();
      /** Ruta solicitada */
      public $action;
      /** Metodo http de la solicitud */
      public $method;
      /** Para la agrupacion de rutas */
      public $group_deep = 0;
      public $group = array();

      public function __construct() 
      {  
         $this->action = Request::uri();
         $this->method = Request::method();

         Event::trigger('server','startup');
      }

      /**
       *   Al ejecutar un metodo que en principio no
       *   existe en la clase, se verifica si este fue
       *   generado dinamicamente y si es asi se llama
       *
       *   @throw Exception cuando el metodo llamado definitivamente no existe
       */
      public function __call($method, array $args) 
      {

         if(preg_match('/^do(\w+)$/', $method, $matches)) {
            $http_method = strtoupper($matches[1]);
            $route = $args[0];
            $callback = $args[1];
            return $this->registreRoute($http_method, $route, $callback);
         }

         trigger_error("Method $method is not member of class " . __CLASS__, E_USER_ERROR);
      }

      /**
       *  Registra una funcion para su respectiva ruta para el
       *  metodo http indicado
       *
       *  @param string $http_method Metodo http en formato UPERCASE
       *  @param string $route uri o ruta para la funcion
       *  @param Clousure $callback funcion que responde a la ruta indicada
       *  @return void
       */
      protected function registreRoute($http_method, $route, $callback) 
      {
         if($this->group_deep > 0)
            $route = '/' . implode('/', $this->group) . $route;

         $route = [
              'pattern' => $route
            , 'callback' => $callback
            , 'watchdog' => array()
         ];

         $this->routes[$http_method][] = $route;

         return $this;
      }

      protected function controllerAsCallback($controller, $args)
      {
         list($controller, $function) = explode('@', $controller);

         $controller_obj = Mvc::controller($controller);

         if(!method_exists($controller_obj, $function)) {
            trigger_error("Method $function is not member of class $controller", E_USER_ERROR);
            return false;
         }

         return call_user_func_array(array($controller_obj, $function), $args);
      }

      public function group($name, $callback) 
      {
         $uri = Request::uriArray();

         if (isset($uri[$this->group_deep]) && ($uri[$this->group_deep] == $name)) {
            
            $this->group[$this->group_deep] = $name;

            $this->group_deep++;
            
            call_user_func($callback);

            $this->group_deep--;

            unset($this->group[$this->group_deep]);
         }

         return $this;
      }

      public function watchdog($name)
      {
         $count = count($this->routes[$this->method]) - 1;

         if(0 > $count)
            return $this;

         $this->routes[$this->method][$count]['watchdog'][] = $name;

         return $this;
      }

      /**
       *   Obtiene las rutas definidas para el metodo solicitado
       *   la compara mediante un patron regular previamente generado
       *   y si la ruta soolicitada a sido definida ejecuta su callback
       *   
       *   @throw Exception cuando la ruta solicitada no esta definida
       *   @return void
       */
      public function run() 
      {

         if(!isset($this->routes[$this->method])) {
            if(false === Event::trigger('server','badRequestMethod', array($this->method)))
               trigger_error("Bad Request Method", E_USER_ERROR);

            return;
         }

         $count = count($this->routes[$this->method]) - 1;

         for ($i = 0; $i <= $count; $i++) {

            $route     = $this->routes[$this->method][$i]['pattern'];
            $callback  = $this->routes[$this->method][$i]['callback'];
            $watchdogs = $this->routes[$this->method][$i]['watchdog'];

            $pattern = Pattern::create($route);

            if(preg_match($pattern, $this->action, $args)) {
              
              array_shift($args);

              foreach ($watchdogs as $watchdog) {
                $args = Watchdog::run($watchdog, $args);
                if(false === $args)
                  return;
              }
              
              if(is_callable($callback))
                return call_user_func_array($callback, $args);
              
              return $this->controllerAsCallback($callback, $args);
            }
         }

         if(false === Event::trigger('server','badRequestUri', array($this->action)))
            trigger_error("Bad Request Uri $this->action", E_USER_ERROR);

         Event::trigger('server','shutdown');
      }
   }