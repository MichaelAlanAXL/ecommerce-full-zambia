<?php

namespace App\Config;

use PDO;

class DB {
    public static function getConnection(): PDO {
        $host = "127.0.0.1";
        $dbname = "ecommerce_zambia";
        $username = "root";
        $password = "";

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}