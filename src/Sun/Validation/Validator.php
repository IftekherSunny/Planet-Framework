<?php

namespace Sun\Validation;

use Violin\Violin;
use Sun\Contracts\Application;
use Sun\Contracts\Security\Hash;
use Sun\Contracts\Session\Session;
use Sun\Contracts\Validation\Validator as ValidatorContract;

class Validator extends Violin implements ValidatorContract
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * @var \Sun\Contracts\Security\Hash
     */
    protected $hash;

    /**
     * @param \Sun\Contracts\Application     $app
     * @param \Sun\Contracts\Session\Session $session
     * @param \Sun\Contracts\Security\Hash   $hash
     */
    public function __construct(Application $app, Session $session, Hash $hash)
    {
        $this->app = $app;

        $this->session = $session;

        $this->hash = $hash;

        $this->addRuleMessage('unique', "The {field} is already taken.");

        $this->addRuleMessage('verify', "{field} does not match.");

    }

    /**
     * To validate unique field
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_unique($value, $input, $args)
    {
        $result = $this->app->db->table(array_shift($args));

        foreach($args as $arg) {
            $result->where($arg, '=', $value);
        }

        if($result->first()) {
            return false;
        }

        return true;
    }

    /**
     * To verify user password
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_verify($value, $input, $args)
    {

        $userModel = config('auth.model');

        $user = $userModel::find($this->session->get('loginUserId'));


        if($user) {

            if($this->hash->verify($value, $user->password)) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * To skip validation rule
     *
     * @return bool
     */
    public function canSkip()
    {
        return true;
    }
}