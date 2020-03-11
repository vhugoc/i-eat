<?php

namespace App\Libraries;

use App\Libraries\Constants;
use Firebase\JWT\JWT;

class Helpers {

  /**
   * @method Generate a JWT
   * @param int $_id User id
   * @param string $_email User email
   * @return string
   */
  public static function generateJWT($_id, $_email, $_type = "user") {
    try {

      $system_constants = Constants::systemConstants();

      // Header Configs
      $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
      ];
      $header = base64_encode(json_encode($header));

      // Payload Configs
      $payload = [
        'iss'    => $system_constants["domain"],
        'id'     => $_id,
        'email'  => $_email,
        'type'   => $_type
      ];
      $payload = base64_encode(json_encode($payload));
      
      // Signature Configs
      $signature = base64_encode(hash_hmac('sha256', "$header.$payload", sha1(md5($system_constants["key"])), true));
      
      return "$header.$payload.$signature";
      
    } catch (Exception $err) {
      return $err->getMessage();
    }
  }

  /**
   * Decode a JWT
   * @param string $_token
   * @return string
   */
  public static function decodeJWT($_token) {
    try {
      $system_constants = Constants::systemConstants();
      
      $token_parts = explode(" ", $_token);
      if (count($token_parts) !== 2 || $token_parts[0] !== "Bearer") {
        return false; exit;
      }

      $_token = $token_parts[1];
      $part = explode(".", $_token);
      
      if (count($part) < 3) {
        return false; exit;
      }
      
      $header = $part[0];
      $payload = $part[1];
      $signature = $part[2];

      $valid = hash_hmac('sha256',"$header.$payload",sha1(md5($system_constants["key"])),true);
      $valid = base64_encode($valid);

      if($signature == $valid) {
        return JWT::decode($_token, sha1(md5($system_constants["key"])), array('HS256'));
      } else {
        return false;
      }

    } catch (Exception $err) {
      return $err->getMessage();
    }
  }
  /**
   * Encrypts a string
   * @param string $_string
   * @return string
   */
  public static function encrypt($_string) :string {
    try {
      return sha1(md5($_string));
    } catch (Exception $err) {
      return $err->getMessage();
    }
  }
  /**
   * Email validation
   * @param string $_email
   * @return string
   */
  public static function validateEmail($_email) :string {
    try {
      return filter_var($_email, FILTER_VALIDATE_EMAIL) ? true : false;
    } catch (Exception $err) {
      return $err->getMessage();
    }
  }
  /**
   * Adjust days/months to any date
   * @param string $_date
   * @param string $_to String to replace. Example: +3 days
   * @param string $_format Date format
   * @return string
   */
  public static function adjustDate($_date, $_to, $_format = 'Y-m-d') :string {
    try {
      return Date($_format, strtotime($_to, strtotime($_date)));
    } catch (Exception $err) {
      return $err->getMessage();
    }
  }
  /**
   * Generate a date with any format
   * @param string $_format
   * @return string
   */
  public static function generateDate($_format) :string {
    $system_constants = Constants::systemConstants();
    date_default_timezone_set($system_constants["timezone"]);
    return Date($_format);
  }
  /**
   * Validate a date/time
   * 
   * @param string $_date
   * @param string $_format
   * @return boolean
   */
  public static function validateDate($_date, $_format = 'Y-m-d H:i:s') {
    $d = \DateTime::createFromFormat($_format, $_date);
    return $d && $d->format($_format) == $_date;
  }
}
