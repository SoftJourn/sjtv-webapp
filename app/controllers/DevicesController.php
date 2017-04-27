<?php

/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 27.04.17
 * Time: 10:09
 */
class DevicesController extends ControllerBase
{
    private $tokens;

    public function onConstruct()
    {
        $this->tokens = explode("\n", file_get_contents($this->di->get('config')->tokensFile));
    }

    public function addAction()
    {
        if (!$token = $this->request->getPost('token')) {
            exit(json_encode([
                'status' => 'error',
                'message' => 'Token not Found'
            ]));
        }
        if (in_array($token, $this->tokens)) {
            exit(json_encode([
                'status' => 'error',
                'message' => 'Token Already Exist'
            ]));
        }
        if (file_put_contents($this->di->get('config')->tokensFile, implode("\n", $this->tokens) . $token . "\n"))
            exit(json_encode([
                'status' => 'success',
                'message' => 'Token was added'
            ]));
        else
            exit(json_encode([
                'status' => 'error',
                'message' => 'Cannot save Token'
            ]));
    }
}