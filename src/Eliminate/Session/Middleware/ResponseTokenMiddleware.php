<?php namespace Eliminate\Session\Middleware;

use Illuminate\Config\Repository;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Encryption\Encrypter;

class ResponseTokenMiddleware implements HttpKernelInterface {

    public function __construct(
        HttpKernelInterface $app,
        Repository $config,
        AuthManager $auth,
        SessionManager $session,
        Encrypter $encrypter) {

        $this->app = $app;
        $this->config = $config;
        $this->auth = $auth;
        $this->session = $session;
        $this->encrypter = $encrypter;
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param Request $request A Request instance
     * @param int $type The type of the request
     *                          (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool $catch Whether to catch exceptions or not
     *
     * @return Response A Response instance
     *
     * @throws \Exception When an Exception occurs during processing
     *
     * @api
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $response = $this->app->handle($request, $type, $catch);

        $sessionId = $this->session->getId();

        $userId = $this->auth->check() ? $this->auth->user()->getAuthIdentifier() : '0';

        $token = $this->encrypter->encrypt($sessionId.'|'.$userId.'|'.time());

        $response->headers->set('X-Response-Token', $token);

        $original = $response->getData();

        $original->token = $token;
        
        $response->setData($original);

        return $response;
    }

}