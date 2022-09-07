<?php

namespace App\Connections;

class DBClientConnection
{
    private string $host;

    public function __construct(string $pathToConfig)
    {
        $config = parse_ini_file($pathToConfig);
        $server = $config['server'];
        $port = $config['port'];

        $this->host = "{$server}:{$port}";
    }

    /**
     * @param string $key
     * @param string $value
     * @return false|string
     */
    public function index(string $key, string $value): false|string
    {
        $uri = "http://{$this->host}/index?{$key}={$value}";
        return file_get_contents($uri);
    }
}
