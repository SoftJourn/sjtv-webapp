<?php
/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 31.05.17
 * Time: 16:08
 */

namespace App\Controllers\Api;


use App\Models\Youtube;
use Firebase\JWT\JWT;

class ItemsController extends ControllerBase
{
    public function indexAction()
    {
        return $this->send($this->playlist->getItems());
    }

    public function updateAction()
    {
        $payload = $this->request->getJsonRawBody();

        if (!isset($payload->data) || empty((array)$payload->data))
            return $this->message('Not found eny settings', 400);
        elseif (!isset($payload->id) || $this->playlist->updateItem($payload->id, (array)$payload->data))
            return $this->message('Item not found!', 400);

        return $this->message('Item has been updated!', 200);

    }

    public function youtubeAction()
    {
        $owner = $this->getDI()->get('jwt')->username;
        $payload = $this->request->getJsonRawBody();
        $url = $payload->youtubeURL ?? NULL;
        $volumeLevel = $payload->volumeLevel ?? NULL;
        if (!$url) {
            return $this->message('Video URL is required!', 400);
        }

        $video = new Youtube($url, $owner, $payload->enabled, $volumeLevel);
        if (!$video->id) {
            return $this->message('Video URL is invalid!', 400);
        }
        $video->setDateAndTime((array)$payload);
        $this->playlist->addItem($video, true);
        if ($this->playlist->save())
            return $this->message('Item has been added!', 200);
        else
            return $this->message('Cannot add Item', 500);
    }

    public function imageAction()
    {
        $user = $this->getDI()->get('jwt');

    }

    public function deleteAction()
    {
        $payload = $this->request->getJsonRawBody();
        if(!$id = $payload->id)
            return $this->message('Item not found', 400);

        if ($this->playlist->removeItem($id)) {
            $this->playlist->save();
            return $this->message('Item has been removed!', 200);
        } else {
            return $this->message('Item not found', 400);
        }
    }

}