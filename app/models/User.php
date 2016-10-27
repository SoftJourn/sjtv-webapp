<?php
namespace App\Models;

use App\Base\UserInterface;

class User implements UserInterface {

    public $login;

    public function __construct($login = null){
      if ($login) {
        $this->login = $login;
      }
    }

    public function isAllowed($controller, $action, $params) {
        return true;
    }

}