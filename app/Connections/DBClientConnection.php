<?php

namespace App\Connections;

use Illuminate\Support\Facades\Http;

class DBClientConnection
{
    private $host;

    public function __construct(string $pathToConfig)
    {
        $config = parse_ini_file($pathToConfig);
        $server = $config['server'];
        $port = $config['port'];

        $this->host = "{$server}:{$port}";
    }

    public function index($key, $value)
    {
        $uri = "{$this->host}/index?{$key}={$value}";
        return Http::get($uri);
    }
}
