<?php
namespace App\Base;

class Controller extends \Phalcon\MVC\Controller {

  public function beforeExecuteRoute($dispatcher) {
    // This is executed before every found action
    if ($this->request->isAjax()) {
      $this->view->disable();
      $this->response->setContentType('application/json');
    }
    $this->view->serverHome = SERVER_HOME;
  }

  public function handleRouteAction() {
    $this->notFoundAction();
  }

  public function notFoundAction() {
    $this->response->setStatusCode(404, 'Not Found');
    $this->view->partial('partial/notFound');
  }
}