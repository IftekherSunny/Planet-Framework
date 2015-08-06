<?php

namespace Sun\Session;

use Exception;
use Sun\Contracts\Session\Session as SessionContract;

class Session implements SessionContract
{
    /**
     * Create a new session instance
     */
    public function __construct()
    {
        $this->startSession();
    }

    /**
     * To start session
     */
    protected function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
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
        $_SESSION[$name] = $value;

        if(isset($_SESSION[$name])) {

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
     * @return string
     * @throws Exception
     */
    public function get($name, $value = '')
    {
        if ($this->has($name)) {

            return $_SESSION[$name] ?: $value;
        }

        if(! empty($value)) {

            return $value;
        }

        throw new Exception("Session [ $name ] does not exists.");
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

        if(! isset($_SESSION[$name])){

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
     * @return string
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
     * @throws Exception
     */
    public function push($name, $value = '')
    {
        if (is_array($this->get($name))) {
            $newValue = $this->get($name);
            $newValue[] = $value;

            return $this->create($name, $newValue);
        }

        throw new Exception("Session [ $name ] does not exists.");
    }

    /**
     * To pop session data from session array
     *
     * @param string $name
     *
     * @return array
     */
    public function pop($name)
    {
        return array_pop($_SESSION[$name]);
    }

    /**
     * To shift session data from session array
     *
     * @param string $name
     *
     * @return array
     */
    public function shift($name)
    {
        return array_shift($_SESSION[$name]);
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
