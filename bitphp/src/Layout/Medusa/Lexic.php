<?php

   namespace Bitphp\Layout\Medusa;

   class Lexic
   {
      private static $base_url;

      public static function baseUrl() 
      {

         if(self::$base_url)
            return self::$base_url;

         $dirname   = dirname($_SERVER['PHP_SELF']);

         $base_url  = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
         $base_url .= $_SERVER['SERVER_NAME'];
         $base_url .= $dirname == '/' ? '' : $dirname;
         
         self::$base_url = $base_url;

         return self::$base_url;
      }

      public static function blockRules()
      {
         return array(
              'T_BLOCK_START' => ':block(\s+)'
            , 'T_BLOCK_END'   => ':endblock'
            , 'T_FULL_BLOCK'  => '/:block(\s+)(\S+)(\s+)(.*):endblock/Usx'
            , 'T_PARENT' => '/:parent/Usx'
         );
      }

      public static function inheritanceRules()
      {
         return array(
              'T_EXTENDS_FULL_PATH' => '/:extends(\s+)#(\S+)(\s+)/Usx'
            , 'T_EXTENDS' => '/:extends(\s+)(\S+)(\s+)/Usx'
         );
      }

      public static function parseRules()
      {
         $rules = [
              '/\{\{(.*)\}\}/U'      # short echo
            , '/:css(\s+)+(.+)/'     # short css include
            , '/:js(\s+)+(.+)/'      # short js include
            , '/:(if|elseif|for|each)(\s+)+(.*)/'  # short statements start
            , '/:(endif|endeach|endfor)/' # short statements end
            , '/:else/'              # else
            , '/\$(\w+)\.(\w+)/'     # short array
            , '/@baseurl/'           # base url
            , '/:include(\s+)+(.*)/' # load
         ];

         $replaces = [
              '<?php echo $1 ?>'
            , '<link rel="stylesheet" href="<?php echo \Alpha\Medusa\Lexic::baseUrl() ?>/static/css/$2.css">'
            , '<script src="<?php echo \Alpha\Medusa\Lexic::baseUrl() ?>/static/js/$2.js"></script>'
            , '<?php $1 ($3): ?>'
            , '<?php $1; ?>'
            , '<?php else: ?>'
            , '$$1["$2"]'
            , '\Alpha\Medusa\Lexic::baseUrl()'
            , '<?php $this->includer(\'$2\') ?>'
         ];

         return array($rules, $replaces);
      }
   }