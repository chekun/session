<?php namespace Eliminate\Session\Application;

use Illuminate\Foundation\Application as IlluminateApplication;

class TokenApplication extends IlluminateApplication {

    /**
     * Get the stacked HTTP kernel for the application.
     *
     * @return  \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getStackedClient()
    {
        $sessionReject = $this->bound('session.reject') ? $this['session.reject'] : null;

        $client = with(new \Stack\Builder)
            ->push('Eliminate\Session\Middleware\RequestTokenMiddleware', $this['config'], $this['encrypter'])
            ->push('Illuminate\Cookie\Guard', $this['encrypter'])
            ->push('Illuminate\Cookie\Queue', $this['cookie'])
            ->push('Eliminate\Session\Middleware\SessionMiddleware', $this['session'], $this['config'], $sessionReject)
            ->push('Eliminate\Session\Middleware\ResponseTokenMiddleware', $this['config'], $this['auth'], $this['session'], $this['encrypter']);

        $this->mergeCustomMiddlewares($client);

        return $client->resolve($this);
    }

}