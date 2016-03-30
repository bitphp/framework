<?php
namespace Bitphp\Http;

/**
 *    This file is part of Bitphp Framework
 *    @author  Eduardo B Romero <ms7rbeta@gmail.com>
 *    @license GNU/GPL v2
 */
class Response 
{
   public static function status($code) 
   {
      $status = [
           200 => 'OK'
         , 201 => 'Created'
         , 202 => 'Accepted'
         , 204 => 'No Content'
         , 301 => 'Moved Permanently'
         , 302 => 'Found'
         , 303 => 'See Other'
         , 304 => 'Not Modified'
         , 400 => 'Bad Request'  
         , 401 => 'Unauthorized'
         , 403 => 'Forbidden'
         , 404 => 'Not Found'  
         , 405 => 'Method Not Allowed'
         , 410 => 'Gone'
         , 415 => 'Unsupported Media Type'
         , 422 => 'Unprocessable Entity'
         , 429 => 'Too Many Requests'
         , 500 => 'Internal Server Error'
      ];

      if (!isset($status[$code])) 
      {
         trigger_error("Invalid status code $code", E_USER_ERROR);
         return;
      }

      $statusMessage = $status[$code];
      header("HTTP/1.1 $code $statusMessage");
   }

   public static function xml($data) 
   {
      header('Content-Type: application/xml;charset=utf-8');
      echo $data;
   }

   public static function json($data) 
   {
      if(is_array($data))
         $data = json_encode($data, JSON_PRETTY_PRINT);

      header('Content-Type: application/json;charset=utf-8');
      echo $data;
   }
}