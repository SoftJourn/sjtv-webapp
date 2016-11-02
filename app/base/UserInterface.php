<?php
namespace App\Base;


interface UserInterface {
    /**
     * Check if requested resource is allowed
     *
     * @param $module
     * @param $controller
     * @param $action
     * @param $params
     * @return mixed
     */
    public function isAllowed($controller, $action, $params);
} 