<?php

namespace Sun\Contracts\Support;

interface Config
{
    /**
     * Loads all configurations from application/config/ php files
     *
     * @param  String $path
     *
     * @return Array
     */
    public function load($path);

    /**
     * Recursively finds an item from the settings array
     *
     * @param  string $item
     * @param  array  $array
     *
     * @return object/$this
     */
    public function find($item = null, $array = null);

    /**
     * Checks if the given item exists in the array
     * that is currently available after find call
     *
     * @return mixed
     */
    public function isExist($key = null);
}