<?php

namespace Sun\Session;

use Sun\Contracts\Application;
use Sun\Contracts\Security\Encrypter;
use Sun\Contracts\Session\Session as SessionContract;

class Session implements SessionContract
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * @var \Sun\Contracts\Security\Encrypter
     */
    protected $encrypter;

    /**
     * @var bool
     */
    protected $expireOnClose;

    /**
     * Session expire time
     *
     * @var int
     */
    protected $expireTime;

    /**
     * Create a new session instance
     *
     * @param \Sun\Contracts\Application        $app
     * @param \Sun\Contracts\Security\Encrypter $encrypter
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        $this->app = $app;
        $this->expireOnClose = $app->config->getSession('expire_on_close')?: false;
        $this->expireTime = $app->config->getSession('expire_time')?: 0;
        $this->encrypter = $encrypter;

        if($this->app->config('session.enable')) {
            $this->startSession();
        }
    }

    /**
     * To start session
     */
    protected function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {

            session_start();

            if(!$this->expireOnClose) {
                setcookie('planet_session', session_id(), time() + ($this->expireTime * 60 ), '/', null, false, false);
            }
        }
    }

    /**
     * To store session data
     *
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function create($name, $value = '')
    {
        if($this->app->config('session.encryption')) {
            $_SESSION[$name] = $this->encrypter->encrypt($value);
        } else {
            $_SESSION[$name] = $value;
        }

        if (isset($_SESSION[$name])) {
            return true;
        }

        return false;
    }

    /**
     * To get session data
     *
     * @param string $name
     * @param string $value
     *
     * @return mixed
     */
    public function get($name, $value = '')
    {
        if ($this->has($name)) {
            if($this->app->config('session.encryption')) {
                return $_SESSION[$name] ? $this->encrypter->decrypt($_SESSION[$name]) : $value;
            } else {
                return $_SESSION[$name] ? $_SESSION[$name] : $value;
            }
        }

        if (!empty($value)) {
            return $value;
        }

        return null;
    }

    /**
     * To delete session data
     *
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        unset($_SESSION[$name]);

        if (!isset($_SESSION[$name])) {
            return true;
        }

        return false;
    }

    /**
     * To check session exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        if (isset($_SESSION[$name])) {
            return true;
        }

        return false;
    }

    /**
     * To pull session data
     *
     * @param string $name
     * @param string $value
     *
     * @return mixed
     */
    public function pull($name, $value = '')
    {
        $value = $this->get($name, $value);
        $this->delete($name);

        return $value;
    }

    /**
     * To push session data into session array
     *
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function push($name, $value)
    {
        $previousValue = $this->get($name, []);

        $previousValue[] = $value;

        return $this->create($name, $previousValue);
    }

    /**
     * To pop session data from session array
     *
     * @param string $name
     *
     * @return string
     */
    public function pop($name)
    {
        $previousValue = $this->get($name);

        $value = array_pop($previousValue);

        $this->create($name, $previousValue);

        return $value;
    }

    /**
     * To shift session data from session array
     *
     * @param string $name
     *
     * @return string
     */
    public function shift($name)
    {
        $previousValue = $this->get($name);

        $value = array_shift($previousValue);

        $this->create($name, $previousValue);

        return $value;
    }

    /**
     * To destroy all session data
     *
     * @return bool
     */
    public function destroy()
    {
        return session_destroy();
    }
}
