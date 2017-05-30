<?php

use App\Models\Playlist;
use Phalcon\Di;

class ApiPlaylistController extends  ApiControllerBase
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