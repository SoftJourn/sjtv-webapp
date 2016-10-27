<?php
namespace App\Plugins\Auth;

use App\Base\AuthInterface;
use App\Models\User;
use Phalcon\Security;

class File implements AuthInterface {

  private $_users;
  private $_file;

  /** @var Security $_security */
  private $_security;

  public function __construct($data) {
    $this->_users = [];
    $this->_security =  \Application::DI()->getShared("security");
    $this->_file = ROOT . $data->path;
    if (!is_file($this->_file)) {
      throw new \Exception('Can\'t find users file: '. $this->_file);
    }
    $data = file($this->_file);
    foreach ($data as $record){
      $info = explode(':', $record);
      if (!$info[0] || !$info[1]) {
        continue;
      }
      $this->_users[trim($info[0])] = trim($info[1]);
    }
  }

  public function auth($username, $password) {
    foreach ($this->_users as $validUser => $passwordHash) {
      if ($username === $validUser) {
        if ($this->_security->checkHash($password, $passwordHash)) {
          return new User($username);
        }
        return false;
      }
    }
    return false;
  }

  public function addUser($username, $password) {
    if (array_key_exists($username, $this->_users)) {
      return 'User already exists';
    }
    $this->_users[$username] = $this->_security->hash($password);
    if ($this->save()) {
      return 'User has been added';
    }
    return 'Can\'t save users file';
  }

  public function updateUser($username, $password) {
    if (!array_key_exists($username, $this->_users)) {
      return 'User doesn\'t exists';
    }
    $this->_users[$username] = $this->_security->hash($password);
    if ($this->save()) {
      return 'User has been updated';
    }
    return 'Can\'t save users file';
  }

  public function deleteUser($username) {
    if (!array_key_exists($username, $this->_users)) {
      return 'User doesn\'t exists';
    }
    unset($this->_users[$username]);
    if ($this->save()) {
      return 'User has been deleted';
    }
    return 'Can\'t save users file';
  }

  private function save() {
    $strings = [];
    foreach ($this->_users as $user => $hash) {
      $strings[]= $user. ':' . $hash;
    }
    return !(false === file_put_contents($this->_file, join("\n",$strings)));
  }

}