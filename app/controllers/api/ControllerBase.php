<?php

namespace App\Controllers\Api;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function send($data) {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));

        return $this->response;
    }


    public function error($message, $code){
        $this->view->disable();
        //todo:: status code
        $this->response->setStatusCode($code);
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode(['error' => $message]));
        return $this->response;
    }

}