<?php namespace Eliminate\Session\Serializer;

class PhpSerializer implements SerializerInterface {

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    public function generateSessionId()
    {
        return sha1(uniqid(true).str_random(25).microtime(true));
    }

    /**
     * serialize session data for the handler to store.
     *
     * @param array $value
     * @return string
     */
    public function serialize(array $value)
    {
        return serialize($value);
    }

    /**
     * Read the session data from the handler.
     *
     * @param string $value
     * @return array
     */
    public function unserialize($value)
    {
        return unserialize($value);
    }
}