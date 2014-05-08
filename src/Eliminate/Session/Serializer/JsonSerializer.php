<?php namespace Eliminate\Session\Serializer;

class JsonSerializer implements SerializerInterface {

    /**
     * Get a new, random session ID using MongoId Style.
     *
     * @return string
     */
    public function generateSessionId()
    {
        static $i = 0;

        $i OR $i = mt_rand(1, 0x7FFFFF);

        return sprintf("%08x%06x%04x%06x",
            time() & 0xFFFFFFFF,
            crc32(substr((string)gethostname(), 0, 256)) >> 16 & 0xFFFFFF,
            getmypid() & 0xFFFF,
            $i = $i > 0xFFFFFE ? 1 : $i + 1
        );
    }

    /**
     * serialize session data for the handler to store.
     *
     * @param array $value
     * @return string
     */
    public function serialize(array $value)
    {
        return json_encode($value);
    }

    /**
     * Read the session data from the handler.
     *
     * @param string $value
     * @return array
     */
    public function unserialize($value)
    {
        return json_decode($value, true);
    }
}