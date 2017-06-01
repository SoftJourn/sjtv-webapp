<?php

namespace App\Models;

class Youtube extends Item
{

    public $id;
    public $url;
    public $volumeLevel;

    public function __construct($url, $owner, $enabled = true, $volumeLevel, $created = null)
    {
        parent::__construct('youtube', $owner, $enabled, $created);
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $this->id = $match[1] ?? false;
        $this->url = 'https://www.youtube.com/watch?v=' . $this->id;
        $this->volumeLevel = $volumeLevel;
    }

} 