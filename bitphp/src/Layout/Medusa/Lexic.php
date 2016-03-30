<?php
namespace Bitphp\Layout\Medusa;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Lexic
{
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
         , '/:(if|elseif|for|foreach)(\s+)+(.*)/'  # short statements start
         , '/:(endif|endforeach|endfor)/' # short statements end
         , '/:else/'              # else
         , '/\$(\w+)\.(\w+)/'     # short array
         , '/@baseurl/'           # base url
         , '/:include(\s+)+(.*)/' # load
      ];

      $replaces = [
           '<?php echo $1 ?>'
         , '<link rel="stylesheet" href="<?php echo \Bitphp\Url::base() ?>/static/css/$2.css">'
         , '<script src="<?php echo \Bitphp\Url::base() ?>/static/js/$2.js"></script>'
         , '<?php $1 ($3): ?>'
         , '<?php $1; ?>'
         , '<?php else: ?>'
         , '$$1["$2"]'
         , '\Bitphp\Url::base()'
         , '<?php self::template_include(\'$2\') ?>' // el eval esta dentro de la instancia de Medusa
      ];

      return array($rules, $replaces);
   }
}