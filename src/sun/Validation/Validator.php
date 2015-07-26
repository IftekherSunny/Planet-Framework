<?php

namespace Sun\Validation;

use Violin\Violin;
use Sun\Session;
use Sun\Security\Encrypter;

class Validator extends Violin
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Encrypter
     */
    private $encrypter;

    /**
     * @param Session   $session
     * @param Encrypter $encrypter
     */
    public function __construct(Session $session, Encrypter $encrypter)
    {
        $this->session = $session;

        $this->encrypter = $encrypter;

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

    public function validate_verify($value, $input, $args)
    {

        $userModel = config('auth.model');

        $user = $userModel::find($this->session->get('loginUserId'));


        if($user) {

            if($this->encrypter->verify($value, $user->password)) {
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