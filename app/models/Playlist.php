<?php

namespace App\Models;
use App\Models\Facebook\Post;
use Phalcon\Exception;


class Playlist
{

    private $_sourceFile = 'playlist.json';

    public $order = 'random';
    public $defaultDuration = '20';
    public $items = [];

    public function __construct()
    {
        $content = file_get_contents($this->_getPath());
        $playlist = json_decode($content);
        if ($playlist) {
            $this->order = $playlist->order;
            $this->defaultDuration = $playlist->defaultDuration;
            foreach ($playlist->items as $item) {
                switch ($item->type) {
                    case 'image':
                        $file = basename($item->url);
                        $objectItem = new Image($file, $item->owner, $item->enabled, $item->duration, $item->created);
                        if ($item->id) {
                            $objectItem->id = $item->id;
                        }
                        break;

                    case 'youtube':
                        $objectItem = new Youtube($item->url, $item->owner, $item->enabled, $item->volumeLevel, $item->created);
                        break;

                    case 'facebook_post':
                        $objectItem = new Post($item->url, $item->owner, $item->enabled, $item->duration, $item->created);
                        if ($item->id) {
                            $objectItem->id = $item->id;
                        }
                        break;
                }
                $objectItem->startDate = $item->startDate;
                $objectItem->endDate = $item->endDate;
                $objectItem->startTime = $item->startTime;
                $objectItem->endTime = $item->endTime;
                $objectItem->enabled = $item->enabled;
                $this->addItem($objectItem);

            }
        }
    }

    protected function _getPath()
    {
        $file = BASE_PATH . '/public/' . $this->_sourceFile;
        if (!file_exists($file)) {
            throw new Exception($file . " doesn't exist");
        }
        return $file;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItemPosition($id)
    {
        foreach ($this->items as $position => $item) {
            if ($item->id === $id) {
                return $position;
            }
        }
        return null;
    }

    public function addItem($item, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->items, $item);
        } else {
            $this->items[] = $item;
        }
        return $this->items;
    }

    public function update($data)
    {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'defaultDuration':
                    $defaultDuration = floatval($value);
                    if ($defaultDuration > 0) {
                        $this->defaultDuration = $defaultDuration;
                        return $this->save();
                    }
                    return false;
                case 'order':
                    if($value == 'true') {
                        $this->order = 'random';
                    } else {
                        $this->order = 'date';
                    }
                    return $this->save();
            }
        }

    }

    public function updateItem($id, $data)
    {
        $pos = $this->getItemPosition($id);
        if (!is_null($pos)) {
            $item = &$this->items[$pos];
            $item->update($data);
            return true;
        }
        return false;
    }

    public function removeItem($id)
    {
        $pos = $this->getItemPosition($id);
        if (!is_null($pos)) {
            $items = array_splice($this->items, $pos, 1);
            $items[0]->purge();
            return true;
        }
        return false;
    }

    public function clear()
    {
        $this->items = [];
        return $this->items;
    }

    public function save()
    {
        $content = json_encode($this);
        return file_put_contents($this->_getPath(), $content);
    }

} 