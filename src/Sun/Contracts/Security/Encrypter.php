<?php

namespace Sun\Contracts\Security;

use Exception;

interface Encrypter
{
    /**
     * To encrypt data
     *
     * @param $data
     *
     * @return string
     */
    public function encrypt($data);

    /**
     * To decrypt data
     *
     * @param $data
     *
     * @return mixed|string
     * @throws Exception
     */
    public function decrypt($data);
}