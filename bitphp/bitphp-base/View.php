<?php

   namespace Bitphp\Base;

   use \Bitphp\Core\Mvc;
   use \Bitphp\Core\Cache;

   class View
   {
      protected $loaded = array();
      /** variables para las vistas */
      protected $vars = array();
      /** resultado de la vista */
      protected $output = '';
      /** la vista antes de evaluarse */
      protected $source = '';

      protected function clean() 
      {
         $this->source  = '';
         $this->output  = '';
         $this->vars    = array();
      }


      protected function make()
      {
         if(empty($this->loaded)) {
            trigger_error("no data was loaded", E_USER_ERROR);
            return;
         }

         Cache::agent('views');

         $cache_id = md5(json_encode(array($this->loaded, $this->vars)));
         $this->output = Cache::read($cache_id);

         if(false !== $this->output)
            return;

         $this->readSources();

         ob_start();

         extract($this->vars);
         eval("?> $this->source <?php ");

         $this->output = ob_get_clean();

         Cache::save($cache_id, $this->output);
      }

      protected function readSources()
      {
        foreach ($this->loaded as $view) {
          $view_source = Mvc::view($view);

          if(false === $view_source) {
            trigger_error("View $view dont exists", E_USER_ERROR);
            return $this;
          }

          $this->source .= $view_source;

        }

        return $this;
      }

      public function load($view)
      {
         $this->loaded[] = $view;
         return $this;
      }

      public function with($vars) 
      {
         $this->vars = $vars;
         return $this;
      }

      /**
       *  Ejecuta make() y muestra directamente el resultado
       *  de la vista
       *
       *  @return void
       */
      public function draw() 
      {
        $this->make();
        echo $this->output;
      }

      /**
       *  Ejecuta make() y retorna el resultado de la vista
       *
       *  @return string resultado de la vista
       */
      public function read() 
      {
        $this->make();
        return $this->output;
      }
   }