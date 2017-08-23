<?php
/**
 * Check if user is allowed to access resource
 *
 * @author Lyubomyr Nykyforuk
 */

namespace App\Plugins;
use \Phalcon\Events\Event;
use \Phalcon\Mvc\Dispatcher;
use \Phalcon\Mvc\User\Component;
use App\Base\UserInterface;

class Acl extends Component
{

    /**
     * Check ACL rules.
     *
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     *
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // get current module, controller and action names:
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $params = $dispatcher->getParams();
        if ($controller == 'login' || $controller == 'devices') {
            return true;
        }

        if ($controller == 'index' && $action == 'rating') { // I think this is a very bad practice... temporary solution
            return true;
        }

        $user = $this->session->get('user');
        if (!($user instanceof UserInterface)) {
            $this->response->redirect("/login/auth?r2=" . $_GET["_url"], true);
            return false;
        }

        if (!$user->isAllowed($controller, $action, $params)) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'pageDenied'
                ]
            );
        }
    }
}
