<?php

class ApiLoginController extends ApiControllerBase
{

    public function indexAction(){
        $this->result($this->request->getPost());
    }

}