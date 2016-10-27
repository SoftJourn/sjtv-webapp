<?php
namespace App\Models;

use Phalcon\Exception;


class Playlist {

  private $_sourceFile = 'playlist.json';

  public $order = 'random';
  public $defaultDuration = '20';
  public $items = [];

  public function __construct(){
    $content = file_get_contents($this->_getPath());
    $playlist = json_decode($content);
    if ($playlist) {
      $this->order = $playlist->order;
      $this->defaultDuration = $playlist->defaultDuration;
      foreach ($playlist->items as $item){
        switch($item->type) {
          case 'image':
            $file = basename($item->url);
            $image = new Image($file, $item->owner, $item->duration, $item->created);
            if ($item->id) {
              $image->id = $item->id;
            }
            $this->addItem($image);
            break;

          case 'youtube':
            $youtube = new Youtube($item->url, $item->owner, $item->created);
            $this->addItem($youtube);
            break;
        }
      }
    }
  }

  protected function _getPath() {
    $file = HTTP_ROOT.$this->_sourceFile;
    if (!file_exists($file)) {
      throw new Exception($file." doesn't exist");
    }
    return $file;
  }

  public function getItems() {
    return $this->items;
  }

  public function getItemPosition($id) {
    foreach ($this->items as $position => $item) {
      if ($item->id === $id) {
        return $position;
      }
    }
    return null;
  }

  public function addItem($item, $prepend=false) {
    if ($prepend) {
      array_unshift($this->items, $item);
    }
    else {
      $this->items[] = $item;
    }
    return $this->items;
  }

  public function update($data) {
    $defaultDuration = floatval($data['defaultDuration']);
    if ($defaultDuration > 0) {
      $this->defaultDuration = $defaultDuration;
      return $this->save();
    }
    return false;
  }

  public function updateItem($id, $data) {
    $pos = $this->getItemPosition($id);
    if (!is_null($pos)) {
      $item = &$this->items[$pos];
      $item->update($data);
      return true;
    }
    return false;
  }

  public function removeItem($id) {
    $pos = $this->getItemPosition($id);
    if (!is_null($pos)) {
      $items = array_splice($this->items, $pos, 1);
      $items[0]->purge();
      return true;
    }
    return false;
  }

  public function clear() {
    $this->items = [];
    return $this->items;
  }

  public function save() {
    $content = json_encode($this);
    return file_put_contents($this->_getPath(), $content);
  }

} 