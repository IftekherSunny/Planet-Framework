<?php

namespace Sun\Session;

use Exception;
use Sun\Contracts\Security\Encrypter;
use Sun\Contracts\Session\Session as SessionContract;

class Session implements SessionContract
{
    /**
     * @var \Sun\Contracts\Security\Encrypter
     */
    protected $encrypter;

    /**
     * Create a new session instance
     *
     * @param \Sun\Contracts\Security\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->startSession();
        $this->encrypter = $encrypter;
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
        $_SESSION[$name] = $this->encrypter->encrypt($value);

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
     * @return string
     * @throws Exception
     */
    public function get($name, $value = '')
    {
        if ($this->has($name)) {

            return $_SESSION[$name] ? $this->encrypter->decrypt($_SESSION[$name]) : $value;
        }

        if (!empty($value)) {

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
     */
    public function push($name, $value)
    {
        $decryptData = $this->get($name, []);

        $decryptData[] = $value;

        return $this->create($name, $decryptData);
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
        $decryptData = $this->get($name);

        $data = array_pop($decryptData);

        $this->create($name, $decryptData);

        return $data;
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
        $decryptData = $this->get($name);

        $data = array_shift($decryptData);

        $this->create($name, $decryptData);

        return $data;
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
