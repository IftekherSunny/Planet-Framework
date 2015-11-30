<?php

namespace Sun\Contracts\Event;

interface Event
{
    /**
     * Bootstrap registered events
     */
    public function register();

    /**
     * Broadcast event
     *
     * @param string $event
     * @param array $data
     *
     * @throws \Sun\Event\EventNotFoundException
     */
    public function broadcast($event, $data);

    /**
     * Event listener to listen broadcast
     *
     * @param string $event
     * @param mixed $handler
     */
    public function listen($event, $handler);

    /**
     * Dispatch all broadcast events
     */
    public function dispatch();
}