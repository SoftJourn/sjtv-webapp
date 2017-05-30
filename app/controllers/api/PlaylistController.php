<?php

namespace App\Controllers\Api;

use App\Models\Playlist;
use Phalcon\Di;

class PlaylistController extends  ControllerBase
{

    /** @var  Playlist $playlist */

    public $playlist;

    public function onConstruct()
    {
        $this->playlist = new Playlist();
    }

    public function indexAction(){
        return $this->send($this->playlist->getItems());
    }

}