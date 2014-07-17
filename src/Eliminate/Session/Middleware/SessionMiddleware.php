<?php namespace Eliminate\Session\Middleware;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Session\Middleware as IlluminateMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Session\SessionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Illuminate\Session\SessionManager;

class SessionMiddleware extends IlluminateMiddleware {


    public function __construct(
        HttpKernelInterface $app,
        SessionManager $manager,
        Repository $config,
        Closure $reject = null) {
        parent::__construct($app, $manager, $reject);
        $this->config = $config;
    }

    /**
     * Add the session cookie to the application response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @param \Illuminate\Session\SessionInterface|\Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return void
     */
    protected function addCookieToResponse(Response $response, SessionInterface $session)
    {
        //no cookies will be sent
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Illuminate\Session\SessionInterface
     */
    public function getSession(Request $request)
    {
        $session = $this->manager->driver();
        $session->setId($this->config->get('session._session_id'));
        return $session;
    }

}