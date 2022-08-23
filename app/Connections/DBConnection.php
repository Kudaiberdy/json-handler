<?php

namespace App\Connections;

class DBConnection extends \PDO
{
    public function __construct(string $pathToConfig)
    {
        $config = parse_ini_file($pathToConfig);
        $server = $config['server'];
        $port = $config['port'];
        $dbname = $config['dbname'];
        $user = $config['user'];
        $password =$config['password'];

        $dsn = "mysql:host={$server};dbname={$dbname}";

        try {
            parent::__construct($dsn, $user, $password);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function index($key, $value)
    {
        $statment = "SELECT * FROM users WHERE $key LIKE '%$value%'";
        try {
            $result = $this->query($statment)->fetchAll(self::FETCH_ASSOC);
            return json_encode($result);
        } catch (\PDOException $e) {
            return $e;
        }
    }
}
