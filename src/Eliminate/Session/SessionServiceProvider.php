<?php namespace Eliminate\Session;

use Illuminate\Session\SessionServiceProvider as IlluminateSessionServiceProvider;

class SessionServiceProvider extends IlluminateSessionServiceProvider {

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->app->bindShared('session', function($app)
        {
            return new SessionManager($app);
        });
    }

}
