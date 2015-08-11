<?php

namespace Sun\Flash;

use Sun\Contracts\Session\Session;
use Sun\Contracts\Flash\Flash as FlashContract;

class Flash implements FlashContract
{
    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new flash instance
     *
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * To show flash message with twitter bootstrap alert-danger
     *
     * @param $message
     */
    public function error($message)
    {
        $this->message($message, 'danger');
    }

    /**
     * To show flash message with twitter bootstrap alert-info
     *
     * @param string $message
     * @param string $level
     */
    public function message($message, $level = 'info')
    {
        $this->session->create('flash_notification.message', $message);
        $this->session->create('flash_notification.level', $level);
    }

    /**
     * To show flash message with twitter bootstrap alert-info
     *
     * @param $message
     */
    public function info($message)
    {
        $this->message($message, 'info');
    }

    /**
     * To show flash message with twitter bootstrap alert-warning
     *
     * @param $message
     */
    public function warning($message)
    {
        $this->message($message, 'warning');
    }

    /**
     * To show flash message with twitter bootstrap alert-success
     *
     * @param $message
     */
    public function success($message)
    {
        $this->message($message, 'success');
    }

    /**
     * To show confirm modal
     *
     * @param $title
     * @param $message
     */
    public function confirm($title, $message)
    {
        $this->overlay($title, $message, true, 'Yes', 'success', 'No');
    }

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
    ) {
        $this->message($message);
        $this->session->create('flash_notification.title', $title);
        $this->session->create('flash_notification.overlay', true);
        $this->session->create('flash_notification.dismissText', $dismissText);
        $this->session->create('flash_notification.dismissType', $dismissType);
        $this->session->create('flash_notification.allowText', $allowText);
        $this->session->create('flash_notification.allowType', $allowType);
        $this->session->create('flash_notification.submitButton', $submitbutton);
    }
}
