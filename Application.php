<?php
/**
 * Application bootstrap.
 * @author Lyubomyr Nykyforuk
 */
class Application extends \Phalcon\Mvc\Application {

    /** @var \Phalcon\DI */
    private static $_di;

    /**
     * Register common resources.
     */
    protected function _registerAutoloaders() {
        // Phalcon autoloader:
        $namespaces = array(
            'App' => ROOT . 'app/',
            'App\Base' => ROOT . 'app/base',
            'App\Models' => ROOT . 'app/models',
            'App\Controllers' => ROOT . 'app/controllers',
            'App\Plugins' => ROOT . 'app/plugins/',
            'App\Plugins\Auth' => ROOT . 'app/plugins/auth',
        );
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces($namespaces)->register();
    }

    /**
     * Register common services.
     */
    protected function _registerServices() {
        // setup Dependency Injector
        $di = \Application::DI();
        $di->setShared("request", function () {
            return new \Phalcon\Http\Request();
        });
        $di->setShared("response", function () {
            return new \Phalcon\Http\Response();
        });
        $di->setShared("tag", function () {
            return new \Phalcon\Tag();
        });
        $di->setShared("escaper", function () {
            return new \Phalcon\Escaper();
        });
        $di->setShared("url", function () {
            return new \Phalcon\Mvc\Url();
        });
        $di->setShared("filter", function () {
            return new \Phalcon\Filter();
        });

        $di->setShared('config', function () {
            $config = require ROOT.'config/main.php';
            return $config;
        });
        $di->setShared('crypt', function() use ($di)  {
            $config = $di->getShared('config');
            $crypt = new Phalcon\Crypt();
            $crypt->setKey($config->cryptKey);
            return $crypt;
        });
        $di->setShared('security', function() {
            $security = new Phalcon\Security();
            $security->setWorkFactor(12);
            return $security;
        });
        $di->setShared('cookies', function() {
            $cookies = new Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(false);
            return $cookies;
        });
        $di->setShared('router', function () use ($di) {
            $config = $di->getShared('config');
            // init new Router instance:
            $router = new \Phalcon\Mvc\Router(false);
            $router->removeExtraSlashes(true);
            $router->setDefaultController('index');
            $router->setDefaultAction('index');
            // add routes from config file:
            $config = require(ROOT.'config/routes.php');
            foreach ($config as $id => $data) {
                $router->add($data['pattern'], $data['params'])->setName($id);
            }
            return $router;
        });
        $di->setShared('dispatcher', function () {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace('App\Controllers');
            $eventsManager = new \Phalcon\Events\Manager();
            $eventsManager->attach('dispatch:beforeExecuteRoute', new \App\Plugins\Acl());
            $eventsManager->attach('dispatch:beforeException',  new \App\Plugins\Exception());
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        $di->setShared('view', function () use ($di) {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../app/views/');
            $view->registerEngines(array(
                '.phtml' => function($view, $di) {
                    $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
                    $volt->setOptions(array(
                        'compiledPath' => ROOT.'cache/volt/',
                        'compiledSeparator' => '_'
                    ));
                    return $volt;
                },
                //'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));
            return $view;
        });
        $di->setShared('session', function () {
            // init and start session:
            $params = array('lifetime' => 604800); // 7 days
            $session = new Phalcon\Session\Adapter\Files($params);
            $session->start();
            return $session;
        });
        $di->setShared('cache', function () {
            // init cache frontend:
            $frontCacheParams = array(array('lifetime' => 86400)); // 1 day
            $frontCache = new Phalcon\Cache\Frontend\Data($frontCacheParams);
            // init cache backend:
            $cacheParams = array('cacheDir' => ROOT . 'cache/data/');
            $cache = new Phalcon\Cache\Backend\File($frontCache, $cacheParams);
            return $cache;
        });
        $di->setShared('time', function() {
          return time();
        });

        $this->setDI($di);
    }


    /**
     * Application bootstrap.
     */
    public function bootstrap() {
        $this->_registerAutoloaders();
        $this->_registerServices();
        return $this;
    }

    /**
     * Provide static access to Dependency Injector
     *
     * @return \Phalcon\DI
     */
    public static function DI()
    {
        if (!self::$_di) {
            self::$_di = new \Phalcon\DI();
        }
        return self::$_di;
    }

}
