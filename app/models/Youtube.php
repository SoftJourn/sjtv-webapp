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
        $this->url = $url;
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->id = $query['v'];
        $this->volumeLevel = $volumeLevel;
    }

} 