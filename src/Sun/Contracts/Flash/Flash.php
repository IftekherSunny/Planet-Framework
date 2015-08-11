<?php

namespace Sun\Contracts\Flash;

interface Flash
{
    /**
     * To show flash message with twitter bootstrap alert-danger
     *
     * @param $message
     */
    public function error($message);

    /**
     * To show flash message with twitter bootstrap alert-info
     *
     * @param string $message
     * @param string $level
     */
    public function message($message, $level = 'info');

    /**
     * To show flash message with twitter bootstrap alert-info
     *
     * @param $message
     */
    public function info($message);

    /**
     * To show flash message with twitter bootstrap alert-warning
     *
     * @param $message
     */
    public function warning($message);

    /**
     * To show flash message with twitter bootstrap alert-success
     *
     * @param $message
     */
    public function success($message);

    /**
     * To show confirm modal
     *
     * @param $title
     * @param $message
     */
    public function confirm($title, $message);

    /**
     * To show flash message with twitter bootstrap model
     *
     * @param string $title
     * @param string $message
     * @param string $allowText
     * @param bool   $submitbutton
     * @param string $allowType
     * @param string $dismissText
     * @param string $dismissType *
     */
    public function overlay(
        $title,
        $message,
        $submitbutton = false,
        $allowText = 'Save',
        $allowType = 'success',
        $dismissText = 'Close',
        $dismissType = 'default'
    );
}