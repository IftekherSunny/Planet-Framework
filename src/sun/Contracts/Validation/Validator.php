<?php

namespace Sun\Contracts\Validation;

interface Validator
{
    /**
     * To validate unique field
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_unique($value, $input, $args);

    /**
     * To verify user password
     *
     * @param $value
     * @param $input
     * @param $args
     *
     * @return bool
     */
    public function validate_verify($value, $input, $args);

    /**
     * To skip validation rule
     *
     * @return bool
     */
    public function canSkip();
}