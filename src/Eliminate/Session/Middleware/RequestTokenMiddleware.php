<?php namespace Eliminate\Session\Middleware;

use Illuminate\Config\Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Illuminate\Encryption\Encrypter;

class RequestTokenMiddleware implements HttpKernelInterface {

    protected $cache = null;

    public function __construct(HttpKernelInterface $app, Repository $config, Encrypter $encrypter)
    {
        $this->app = $app;
        $this->config = $config;
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

        $token = $request->headers->get('X-Request-Token');

        if (! is_null($token)) {

            $tokenValue = explode('|', $this->encrypter->decrypt($token));

            $this->cache = app('cache');

            app()->before(function() use ($tokenValue){

                if (\Auth::check() and is_null(\Session::get(\Auth::getName()))) {
                    \Session::put(\Auth::getName(), $tokenValue[1]);
                }
            });

            if ($this->cache->get('token.'.$tokenValue[3])) {
                $this->config->set('session._session_id', $tokenValue[0]);
                $this->config->set('session._timestamp', $tokenValue[2]);
                $this->cache->forget('token.'.$tokenValue[3]);
            }



        }

        $response = $this->app->handle($request, $type, $catch);

        return $response;
    }

}
