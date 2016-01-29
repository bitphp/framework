<?php

   namespace Bitphp\Http;

   class Response 
   {
      protected static $statusCode;
      protected static $statusMessage = 'OK';

      protected static function getStatusCode() 
      {
         return empty(self::$statusCode) ? 200 : self::$statusCode;
      }

      public static function status($code) 
      {
         $status = [
            200 => 'OK',  
            201 => 'Created',  
            202 => 'Accepted',  
            204 => 'No Content',  
            301 => 'Moved Permanently',  
            302 => 'Found',  
            303 => 'See Other',  
            304 => 'Not Modified',
            400 => 'Bad Request',  
            401 => 'Unauthorized',  
            403 => 'Forbidden',  
            404 => 'Not Found',  
            405 => 'Method Not Allowed',
            410 => 'Gone',
            415 => 'Unsupported Media Type',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error'
         ];

         if ( !isset( $status[ $code ] ) ) {
            trigger_error("Invalid status code $code", E_USER_ERROR);
            return;
         }

         self::$statusMessage = $status[$code];
         header("HTTP/1.1 $code " . self::$statusMessage);
      }

      public static function getStatusMessage() {
         return self::$statusMessage;
      }

      public static function xml( $data ) {
         header( 'Content-Type: application/xml;charset=utf-8' );
         echo $data;
      }

      public static function json( $data ) {
         if(is_array($data))
            $data = json_encode($data, JSON_PRETTY_PRINT);

         header( 'Content-Type: application/json;charset=utf-8' );
         echo $data;
      }
   }