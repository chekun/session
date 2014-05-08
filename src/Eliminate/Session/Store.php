<?php namespace Eliminate\Session;

use Illuminate\Session\Store as IlluminateSessionStore;
use SessionHandlerInterface;
use Eliminate\Session\Serializer\SerializerInterface;

class Store extends IlluminateSessionStore {

    /**
     * The session serializer implementation.
     *
     * @var \SerializerInterface
     */
    protected $serializer;

    /**
     * Create a new session instance.
     *
     * @param  string $name
     * @param \SessionHandlerInterface $handler
     * @param Serializer\SerializerInterface $serializer
     * @param  string|null $id
     * @return \Eliminate\Session\Store
     */
    public function __construct($name, SessionHandlerInterface $handler, SerializerInterface $serializer, $id = null)
    {
        $this->setSerializer($serializer);
        parent::__construct($name, $handler, $id);
    }


    /**
     * Read the session data from the handler.
     *
     * @return array
     */
    protected function readFromHandler()
    {
        $data = $this->handler->read($this->getId());
        return $data ? $this->serializer->unserialize($data) : array();
    }

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function generateSessionId()
    {
        return $this->serializer->generateSessionId();
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->addBagDataToSession();

        $this->ageFlashData();

        $this->handler->write($this->getId(), $this->serializer->serialize($this->attributes));

        $this->started = false;
    }

    /**
     * Set a serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get the serializer
     *
     * @return \SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

}
