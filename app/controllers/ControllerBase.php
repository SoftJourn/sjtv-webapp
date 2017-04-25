<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function beforeExecuteRoute(\Phalcon\Dispatcher $dispatcher)
    {
        // This is executed before every found action
        if ($this->request->isAjax()) {
            $this->view->disable();
            $this->response->setContentType('application/json');
        }
        $this->view->serverHome = $this->getDI()->get('config')->application->baseUri;
    }

    public function handleRouteAction()
    {
        $this->notFoundAction();
    }

    public function notFoundAction()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->partial('partial/notFound');
    }

}
