<?php
namespace App\Base;


interface AuthInterface {

    /**
     * Check if user is valid
     *
     * @param $username
     * @param $password
     * @return boolean|UserInterface
     */
    public function auth($username, $password);
} 