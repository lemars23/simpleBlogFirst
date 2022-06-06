<?php
namespace Models;

use PDO;
use PDOException;

class Model
{
    protected $table;

    private static $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private static function getUsername()
    {
        return $_ENV["USERNAME"];
    }

    private static function getUserpass()
    {
        return $_ENV["USERPASS"];
    }

    private static function getHost()
    {
        return $_ENV["HOST"];
    }

    private static function getDbname()
    {
        return $_ENV["DB_NAME"];
    }

    private static function getDsn()
    {
        return "mysql:host=" . self::getHost() . ";dbname=" . self::getDbname();
    }

    private static function db_connecting()
    {
        try {
            return new PDO(self::getDsn(), self::getUsername(), self::getUserpass(), self::$opt);
        } catch (PDOException $e) {
            die("PDO connecting error: " . $e->getMessage());
        }
    }

    protected static function execute(?string $sql,?array $params = [], string $className = self::class) : ?array
    {
        $stmt = self::db_connecting()->prepare($sql);
        $result = $stmt->execute($params);

        if(false === $result) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS,$className);
    }

    

    
}