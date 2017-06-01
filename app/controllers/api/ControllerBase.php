<?php

namespace App\Controllers\Api;

use App\Models\Playlist;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public $playlist;

    public function onConstruct()
    {
        $this->playlist = new Playlist();
    }

    public function send($data)
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));

        return $this->response;
    }

    public function message($message, $code)
    {
        $this->view->disable();
        //todo:: status code
        $this->response->setStatusCode($code);
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode(['message' => $message]));
        return $this->response;
    }

}