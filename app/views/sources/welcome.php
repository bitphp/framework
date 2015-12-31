<!DOCTYPE html>
<html>
<head>
   <title>Bitphp Framework</title>
   <style type="text/css">
      #global-container, body, html { width: 100%; height: 100%; padding: 0; margin: 0; }
      #global-container { display: table; }
      #global { display: table-cell; vertical-align: middle; }
      #logo { width: 50%; }
   </style>
</head>
<body>
   <div id="global-container">
      <div id="global" align="center">
         <img id="logo" src="<?php echo \Bitphp\Core\Route::baseUrl() ?>/public/img/bitphp.png">
      </div>
   </div>
</body>
</html>