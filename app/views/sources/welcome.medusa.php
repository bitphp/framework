<!DOCTYPE html>
<html>
<head>
   <title>Bitphp Framework</title>
   <style type="text/css">
      * {
        box-sizing: border-box;
      }

      html, body, #global-container, #content {
         width: 100%;
         height: 100%;
         margin: 0;
         font-family: monospace;
      }

      #global-container {
         padding: 25px;
         display: table;
      }

      #content {
         display: table-cell;
         vertical-align: middle;
         align-content: center;
         text-align: center;
      }

      .bitphp-logo {
         width: 100%;
         max-width: 60px;
      }

      .purple {
         color: #6E28C9;
      }
   </style>
</head>
<body>
   <div id="global-container">
      <div id="content">
         <img src="{{ @baseurl }}/static/img/bitphp.png" class="bitphp-logo">
         <p>
            Bitphp working on <span class="purple">{{ @baseurl }}</span> with <span class="purple">PHP {{ phpversion() }}</span>
         </p>
      </div>
   </div>
</body>
</html>