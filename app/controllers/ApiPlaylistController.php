<?php

use App\Models\Playlist;
use Phalcon\Di;

class ApiPlaylistController extends ApiControllerBase
{

    /** @var  Playlist $playlist */

    public $playlist;

    public function onConstruct()
    {
        $this->playlist = new Playlist();
    }

    public function indexAction(){
        $this->result($this->playlist->getItems());
    }

    public function addAction(){

    }
}