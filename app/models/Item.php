<?php


class Item {

  public $id;
  public $type;
  public $owner;
  public $created;

  public function __construct($type, $owner, $created=null) {
    $this->id = uniqid();
    $this->type = $type;
    $this->owner = $owner;
    $this->created = ($created ? $created :time());
  }

  public function update($data) {

  }

  public function purge() {

  }

  public function setID($id) {
    $this->id = $id;
  }
} 