<?php

namespace App\Controllers\Api;

use \App\Base\UserInterface;
use \Firebase\JWT\JWT;

class LoginController extends ControllerBase
{

    public function indexAction()
    {
        $response = [
            'status' => false
        ];
        if($payload = $this->request->getJsonRawBody()) {
            $login = $payload->username;
            $password = $payload->password;
            if ($login && $password) {
                $adapters = $this->di->getShared('config')->auth;
                foreach ($adapters as $type => $data) {
                    if ($data->enabled) {
                        $adapterClass = '\App\Plugins\Auth\\' . $type;
                        /** @var AuthInterface $adapter */
                        $adapter = new $adapterClass($data);
                        $user = $adapter->auth($login, $password);
                        if ($user instanceof UserInterface) {
                            $response = [
                                'status' => true,
                                'token' => JWT::encode($payload, $this->di->get('config')->apiKey),
                                'user' => $user
                            ];
                        }
                    }
                }
            }
        }

        return $this->send($response);

    }
}
