<?php namespace Eliminate\Session\Serializer;

use Illuminate\Support\MessageBag;

class JsonSerializer implements SerializerInterface {

    /**
     * Get a new, random session ID using MongoId Style.
     *
     * @return string
     */
    public function generateSessionId()
    {
        static $i = 0;

        $i or $i = mt_rand(1, 0x7FFFFF);

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
        if (isset($value['errors']) and $value['errors'] instanceof MessageBag) {
            $value['errors'] = serialize($value['errors']);
        }
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
        $result = json_decode($value, true);
        if (isset($result['errors']) ) {
            $result['errors'] = unserialize($result['errors']);
        }
        return $result;
    }
}