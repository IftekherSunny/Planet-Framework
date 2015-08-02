<?php

namespace Sun\Validation;

use Sun\Session;
use Violin\Violin;
use Sun\Security\Hash;

class Validator extends Violin
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Hash
     */
    private $hash;

    /**
     * @param Session   $session
     * @param Hash $hash
     */
    public function __construct(Session $session, Hash $hash)
    {
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
        $userModel = config('auth.model');

        $user = $userModel::where($args[0],'=',$value)->first();


        if($user) {

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

    public function canSkip()
    {
        return true;
    }
}