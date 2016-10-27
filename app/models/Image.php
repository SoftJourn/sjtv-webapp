<?php
namespace App\Models;


class Image extends Item {

  public $url;
  public $thumbnail;
  public $duration;

  public static $filesDir = 'uploads/';
  public static $supportedTypes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png'
  ];
  protected $_file;

  public function __construct($file, $owner, $duration=null, $created=null){
    parent::__construct('image', $owner);
    $this->_file = $file;
    $this->url = SERVER_HOME.self::$filesDir.$file;
    $this->thumbnail = SERVER_HOME.self::$filesDir.'thumbs/'.$file;
    $this->duration = $duration;
  }

  public function update($data) {
    if (array_key_exists('duration', $data)) {
      $this->duration = floatval($data['duration']);
    }
  }


  public function purge() {
    $path = HTTP_ROOT.self::$filesDir.$this->_file;
    if (file_exists($path)) {
      unlink($path);
    }
    $thumbnail = HTTP_ROOT.self::$filesDir.'thumbs/'.$this->_file;
    if (file_exists($thumbnail)) {
      unlink($thumbnail);
    }
  }



} 