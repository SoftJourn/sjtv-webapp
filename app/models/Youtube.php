<?php
namespace App\Models;


class Youtube extends Item {

  public $id;
  public $url;

  public function __construct($url, $owner, $created=null){
    parent::__construct('youtube', $owner, $created);
    $this->url = $url;
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    $this->id = $query['v'];
  }

} 