<?php

   namespace Bitphp\Layout;

   use \Bitphp\Layout\Medusa\Cache;
   use \Bitphp\Layout\Medusa\Compiler;

   class Medusa
   {
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
            $view_file = "../app/views/sources/$view.medusa.php";

            if(!file_exists($view_file)) {
              trigger_error("View $view_file dont exists", E_USER_ERROR);
              return $this;
            }

            $this->source .= Compiler::parse(file_get_contents($view_file));
         }
         
         return $this;
      }

      protected function includer($view)
      {
        $medusa = new Medusa();
        
        $medusa
          ->load($view)
          ->with($this->vars)
          ->draw();

        $medusa = null;
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

      public function draw() 
      {
        $this->make();
        echo $this->output;
      }

      public function read() 
      {
        $this->make();
        return $this->output;
      }
   }