<?php

namespace App\Libraries;

trait initializeConstants {

  public $data;
  
  public static function databaseConstants() {
    $data = [
      "host"      => "localhost",
      "name"      => "ieat",
      "user"      => "root",
      "password"  => ""
    ];
    return $data;
  }

  public static function systemConstants() {
    $data = [
      "key"       => "ieat_key",
      "domain"    => "http://localhost:8000/api",
      "timezone"  => "America/Sao_Paulo"
    ];
    return $data;
  }
}

class Constants {
  use initializeConstants;
}
