<?php
/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 15.05.17
 * Time: 13:27
 */

namespace App\Models\Facebook;


class Video extends Base
{
    public $volumeLevel;
    public $url;

    public function __construct($url, $owner, $volumeLevel, $enabled = true, $created = null)
    {
        parent::__construct('facebook_video', $owner, $enabled, $created);
        $this->url = $url;
        $this->volumeLevel = $volumeLevel;
    }
}