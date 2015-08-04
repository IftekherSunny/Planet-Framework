<?php

namespace Sun\Bus;

use ReflectionClass;

class CommandTranslator
{
    /**
     * To translate command to command handler
     *
     * @param $object
     *
     * @return string
     * @throws CommandNotFoundException
     * @throws HandlerNotFoundException
     */
    public function translate($object)
    {
        $commandBaseNamespace = app()->getNamespace() . "Commands";
        $handlerBaseNamespace = app()->getNamespace(). "Handlers";

        $reflectionObject = new ReflectionClass($object);

        $commandNamespace = $this->getCommandNamespace($commandBaseNamespace, $reflectionObject);
        $command = $this->getCommandName($reflectionObject);

        $handler = str_replace('Command', 'CommandHandler', $command);


        if($commandNamespace !== $commandBaseNamespace) {
            $handlerNamespace = $handlerBaseNamespace . $commandNamespace . "\\" . $handler;

            $commandNamespace = $commandBaseNamespace .  $commandNamespace . "\\" . $command;
        }


        if(! class_exists($commandNamespace)) {
            throw new CommandNotFoundException("Command [ $command ] does not exist.");
        }

        if(! class_exists($handlerNamespace)) {
            throw new HandlerNotFoundException("Handler [ $handler ] does nto exist.");
        }

        return $handlerNamespace;
    }

    /**
     * To get command namespace
     *
     * @param $commandBaseNamespace
     * @param $reflectionObject
     *
     * @return mixed
     */
    private function getCommandNamespace($commandBaseNamespace, $reflectionObject)
    {
        return str_replace($commandBaseNamespace, "", $reflectionObject->getNamespaceName());
    }

    /**
     * To get command name
     *
     * @param $reflectionObject
     *
     * @return mixed
     */
    private function getCommandName($reflectionObject)
    {
        return $reflectionObject->getShortName();
    }
}