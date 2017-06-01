<?php
/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 31.05.17
 * Time: 12:33
 */

namespace App\Controllers\Api;


class SettingsController extends ControllerBase
{

    public function indexAction()
    {
        return $this->send($this->playlist->getSettings());
    }

    public function setAction()
    {
        if($this->send($this->playlist->update($this->request->getJsonRawBody())))
            return $this->message('Playlist settings have been updated', 200);
        else
            return $this->message('Playlist settings update failed', 400);

    }
}