<?php 

namespace Sun\Event;

use ReflectionClass;
use Sun\Contracts\Application;
use Sun\Contracts\Event\Event as EventContract;

class Event implements EventContract
{
    /**
     * Planet application
     *
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * Registered events
     *
     * @var array
     */
    protected $events = [];

    /**
     * Broadcast events
     *
     * @var array
     */
    protected $broadcast = [];

    /**
     * Create a new event instance
     *
     * @param \Sun\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap registered events
     */
    public function register()
    {
        $events = $this->app->config('event');

        foreach($events as $name => $handlers) {
            $this->events[$name] = $handlers;
        }
    }

    /**
     * Broadcast event
     *
     * @param string $event
     * @param array $data
     *
     * @throws \Sun\Event\EventNotFoundException
     */
    public function broadcast($event, $data)
    {
        if(!array_key_exists($event, $this->events)) {
            throw new EventNotFoundException("Event [ {$event} ] does not exists.");
        }

        $this->broadcast[$event] = $data;
    }

    /**
     * Event listener to listen broadcast
     *
     * @param string $event
     * @param mixed $handler
     */
    public function listen($event, $handler)
    {
        if(array_key_exists($event, $this->broadcast)) {

            $eventObject = $this->getEventObject($event, $this->broadcast[$event]);

            if(is_callable($handler)) {
                call_user_func_array($handler, [$eventObject]);
            } else {
                call_user_func_array([$this->app->make($handler), 'listen'], [$eventObject]);
            }
        }
    }

    /**
     * Dispatch all broadcast events
     */
    public function dispatch()
    {
        foreach($this->broadcast as $event => $data) {
            $this->execute($event);
        }
    }

    /**
     * Listening all broadcast events
     *
     * @param string $event
     */
    protected function execute($event)
    {
        foreach($this->events[$event] as $handler) {
            $this->listen($event, $handler);
        }
    }

    /**
     * Get event class namespace
     *
     * @param string $className
     *
     * @return string
     */
    protected function getEventClass($className)
    {
        return $this->app->getNamespace() . "Events\\{$className}";
    }

    /**
     * Get event class object
     *
     * @param string $event
     * @param array  $data
     *
     * @return object
     */
    protected function getEventObject($event, $data)
    {
        $eventClass = new ReflectionClass($this->getEventClass($event));

        return $eventClass->newInstanceArgs($data);
    }
}