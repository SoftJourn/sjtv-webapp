<?php

namespace App\Plugins\Auth;

use App\Base\AuthInterface;
use App\Models\User;

class Ldap implements AuthInterface
{

    private $_cnf;

    public function __construct($data)
    {
        $this->_cnf = $data;
    }

    public function auth($username, $password)
    {

        $ldapRDN = $this->_cnf->uid . '=' . $username . ',' . $this->_cnf->dn;
        $connection = ldap_connect($this->_cnf->host, $this->_cnf->port);
        if ($connection && @ldap_bind($connection, $ldapRDN, $password)) {
            return new User($username);
        }
        return false;
    }
}