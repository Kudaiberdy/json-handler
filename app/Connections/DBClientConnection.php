<?php

namespace App\Connections;

use Illuminate\Support\Facades\Http;

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
     * @return \Illuminate\Http\Client\Response
     */
    public function index(string $key, string $value): \Illuminate\Http\Client\Response
    {
        $uri = "{$this->host}/index?{$key}={$value}";
        return Http::get($uri);
    }
}
