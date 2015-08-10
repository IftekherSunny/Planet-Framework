<?php

namespace Sun\Contracts\Session;

use Exception;

interface Session
{
    /**
     * To store session data
     *
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function create($name, $value = '');

    /**
     * To get session data
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     * @throws Exception
     */
    public function get($name, $value = '');

    /**
     * To delete session data
     *
     * @param string $name
     *
     * @return bool
     */
    public function delete($name);

    /**
     * To check session exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * To pull session data
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     */
    public function pull($name, $value = '');

    /**
     * To push session data into session array
     *
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function push($name, $value);

    /**
     * To pop session data from session array
     *
     * @param string $name
     *
     * @return array
     */
    public function pop($name);

    /**
     * To shift session data from session array
     *
     * @param string $name
     *
     * @return array
     */
    public function shift($name);

    /**
     * To destroy all session data
     *
     * @return bool
     */
    public function destroy();
}