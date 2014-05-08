<?php namespace Eliminate\Session\Serializer;


interface SerializerInterface {

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    public function generateSessionId();

    /**
     * serialize session data for the handler to store.
     *
     * @param array $value
     * @return string
     */
    public function serialize(array $value);

    /**
     * Read the session data from the handler.
     *
     * @param string $value
     * @return array
     */
    public function unserialize($value);

}