<?php declare(strict_types=1);

use React\Cache\CacheInterface;
use React\Dns\Config\Config;
use React\Dns\Resolver\Factory;
use React\Dns\Resolver\Resolver;
use React\EventLoop\LoopInterface;
use WyriMaps\BattleNet\AsyncClient;
use WyriMaps\BattleNet\AsyncClientInterface;
use WyriMaps\BattleNet\Authentication\ClientCredentials;
use WyriMaps\BattleNet\Middleware\ClientCredentialsMiddleware;
use WyriMaps\BattleNet\Options;
use function DI\factory;
use function DI\get;

return [
    Resolver::class => factory(function (
        string $server,
        CacheInterface $cache,
        LoopInterface $loop
    ) {
        $config = Config::loadSystemConfigBlocking();
        $server = $config->nameservers ? reset($config->nameservers) : $server;

        return (new Factory())->createCached($server, $loop, $cache);
    })
        ->parameter('server', get('config.react.dns.server'))
        ->parameter('cache', get('config.react.dns.cache')),
];
