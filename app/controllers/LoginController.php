<?php

use App\Base\UserInterface;

class LoginController extends ControllerBase
{

    public function authAction()
    {

        if ($this->session->get("user") instanceof UserInterface) {
            $this->response->redirect("/", true);
            return true;
        }
        if ($this->request->isPost()) {
            $user = false;
            $login = $this->request->get("login", "string");
            $password = $this->request->get("password", "string");
            if ($login && $password) {

                $adapters = $this->di->getShared('config')->auth;
                if (!count($adapters)) {
                    throw new \Exception("No auth adapters defined.");
                }
                foreach ($adapters as $type => $data) {
                    if ($data->enabled) {
                        $adapterClass = '\App\Plugins\Auth\\' . $type;
                        /** @var AuthInterface $adapter */
                        $adapter = new $adapterClass($data);
                        $user = $adapter->auth($login, $password);
                        if ($user instanceof UserInterface) {
                            $this->session->set("user", $user);
                            $redirect = $this->request->getQuery("r2") ?: $this->di->get('config')->appication->baseUri;
                            return $this->response->redirect($redirect, true);
                        }
                    }
                }
                $this->view->error = "Wrong username or password";
            } else {
                $this->view->error = "Specify username and password";
            }
            $this->view->login = $login;
        }

    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect($this->di->get('config')->appication->baseUri, true);
    }

}

