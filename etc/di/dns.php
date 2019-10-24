<?php declare(strict_types=1);

use React\Cache\CacheInterface;
use React\Dns\Config\Config;
use React\Dns\Resolver\Factory;
use React\Dns\Resolver\ResolverInterface;
use React\EventLoop\LoopInterface;
use function DI\factory;
use function DI\get;

return [
    ResolverInterface::class => factory(function (
        string $server,
        CacheInterface $cache,
        LoopInterface $loop
    ) {
        $config = Config::loadSystemConfigBlocking();
        $server = $config->nameservers ? reset($config->nameservers) : $server;

        return (new Factory())->createCached($server, $loop, $cache);
    })
        ->parameter('server', get('config.react.dns.fallback_server'))
        ->parameter('cache', get('config.react.dns.cache')),
];
