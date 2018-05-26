<?php

class Connection {
    private static $DB_SERVER_NAME = "localhost";
    private static $DB_NAME = "sojcaci_angular";
    private static $DB_READ_USER = "sojcaci_angular";
    private static $DB_WRITE_USER = "sojcaci_angular";
    private static $DB_PASSWORD = "sojcaci_angular";

    //connect to SOJCACI_RTD
    public static function connectForRead() {
      $connection = mysqli_connect(self::$DB_SERVER_NAME, self::$DB_READ_USER, self::$DB_PASSWORD, self::$DB_NAME) or die('Unable to connect: ' . mysqli_error($connection));
      return $connection;
    }

    //connect to SOJCACI_LTD
    public static function connectForReadWrite() {
      $connection = mysqli_connect(self::$DB_SERVER_NAME, self::$DB_WRITE_USER, self::$DB_PASSWORD, self::$DB_NAME) or die('Unable to connect: ' . mysqli_error($connection));
      return $connection;
    }

}

?>
