<?php

namespace Sun\Contracts\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

interface Database
{
    /**
     * To get Capsule instance
     *
     * @return Capsule
     */
    public function getCapsuleInstance();

    /**
     * To boot Eloquent
     */
    public function boot();
}