<?php

   namespace Bitphp\Layout\Medusa;

   class Compiler
   {
      protected static $inheritance_blocks = array();

      protected static function readBlocks($source)
      {
         $rules = Lexic::blockRules();

         preg_match_all($rules['T_FULL_BLOCK'], $source, $tokens);

         $blocks = count($tokens[0]);
         for($i = 0; $i < $blocks; $i++) {

            $name = $tokens[2][$i];
            $content = $tokens[4][$i];

            if(isset(self::$inheritance_blocks[$name])) {
               if(1 === preg_match($rules['T_PARENT'], $content)) {
                  $content = preg_replace($rules['T_PARENT'], self::$inheritance_blocks[$name], $content);
                  self::$inheritance_blocks[$name] = $content;
                  continue;
               }
            }

            self::$inheritance_blocks[$name] = $content;
         };
      }

      protected static function writeBlocks($source)
      {
         $rules = Lexic::blockRules();

         foreach (self::$inheritance_blocks as $name => $value) {
            $pattern = '/' . $rules['T_BLOCK_START'] . $name . '(\s+)(.*)' . $rules['T_BLOCK_END'] . '/Usx';
            $source = preg_replace($pattern, $value, $source);
         }

         return $source;
      }

      protected static function applyInheritance($source)
      {
         $rules = Lexic::inheritanceRules();

         preg_match_all($rules['T_EXTENDS_FULL_PATH'], $source, $matches);

         if(empty($matches[2])){
            
            preg_match_all($rules['T_EXTENDS'], $source, $matches);
            
            if(empty($matches[2]))
               return $source;

            $view = $matches[2][0];
            $view_file = "../app/views/sources/$view.medusa.php";
            
            if(file_exists($view_file))
               $parent_source = file_get_contents($view_file);

         } else {

            $view = $matches[2][0];

            if(!file_exists($view)) {
              $parent_source = false;
            } else {
              $parent_source = file_get_contents($view);
            }
         }

         if(false === $parent_source) {
            trigger_error("Extends error: View $view dont exists", E_USER_ERROR);
         }

         self::readBlocks($parent_source);
         self::readBlocks($source);

         if(empty(self::$inheritance_blocks))
            return $parent_source;

         return self::writeBlocks($parent_source);
      }

      public static function parse($source)
      {
         $source = self::applyInheritance($source);
         list($rules, $replaces) = Lexic::parseRules();
         return preg_replace($rules, $replaces, $source);
      }
   }