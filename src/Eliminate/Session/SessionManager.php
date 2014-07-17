<?php namespace Eliminate\Session;

use Illuminate\Cache\Repository;
use Illuminate\Session\SessionManager as IlluminateSessionManager;
use Illuminate\Session\CacheBasedSessionHandler;
use Eliminate\Session\Serializer\JsonSerializer;
use Eliminate\Session\Serializer\PhpSerializer;

class SessionManager extends IlluminateSessionManager {

    /**
     * Create an instance of the Redis session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createRedisDriver()
    {

        $redis = $this->app['redis'];

        $prefix = $this->app['config']['session.prefix'] ?: '';

        $repository = new Repository(new RedisStore($redis, $prefix));

        $minutes = $this->app['config']['session.lifetime'];

        $handler = new CacheBasedSessionHandler($repository, $minutes);

        $handler->getCache()->getStore()->setConnection($this->app['config']['session.connection']);

        return $this->buildSession($handler);
    }

    /**
     * Build the session instance.
     *
     * @param  \SessionHandlerInterface  $handler
     * @return \Store
     */
    protected function buildSession($handler)
    {
        $preferSerializer = $this->app['config']['session.serializer'] ?: 'php';

        $serializer = null;

        if ($preferSerializer == 'json') {
            $serializer = new JsonSerializer();
        }  else {
            $serializer = new PhpSerializer();
        }

        return new Store($this->app['config']['session.cookie'], $handler, $serializer, null);

    }

}