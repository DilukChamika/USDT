<?php

namespace app\common\model;

use think\Model;

class Database extends Model {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dsn = 'mysql:host=localhost;dbname=upay_db;charset=utf8mb4';
                self::$connection = new PDO($dsn, 'root', '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Database Connection Failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
?>
