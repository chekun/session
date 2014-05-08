<?php namespace Eliminate\Session;

use Illuminate\Cache\RedisStore as IlluminateCacheRedisStore;

class RedisStore extends IlluminateCacheRedisStore {

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {
        if ( ! is_null($value = $this->connection()->get($this->prefix.$key)))
        {
            return $value;
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        $this->connection()->set($this->prefix.$key, $value);

        $this->connection()->expire($this->prefix.$key, $minutes * 60);
    }

}