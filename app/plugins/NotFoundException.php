<?php

namespace App\Plugins;
use Phalcon\Mvc\User\Component;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class NotFoundException extends Component
{

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        //Handle 404 exceptions
        if ($exception instanceof DispatchException) {
            $controller = $dispatcher->getControllerName();
            $class = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';
            if (!class_exists($class)) {
                $controller = 'index';
            }
            $dispatcher->forward(
                [
                    'controller' => $controller,
                    'action' => 'handleRoute'
                ]
            );
        } else {
            //log exception to error log
            $message = $exception->getFile() . ":" . $exception->getLine() . "  " . $exception->getMessage();
            error_log($message);
        }
        return false;
    }
}