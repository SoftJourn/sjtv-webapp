<?php
/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 11.05.17
 * Time: 15:27
 */

namespace App\Models\Facebook;


class Post extends Base
{

    public $duration;
    public $url;

    public function __construct($url, $owner, $enabled = true, $duration = null, $created = null)
    {
        parent::__construct('facebook_post', $owner, $enabled, $created);
        $this->url = $url;
        $this->duration = $duration;
    }

}