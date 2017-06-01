<?php

namespace App\Controllers\Api;

use App\Models\Playlist;
use App\Plugins\FCMNotifications;
use Phalcon\Di;

class PlaylistController extends ControllerBase
{

    public function indexAction()
    {
        return $this->send($this->playlist->getItems());
    }

    public function playNowAction()
    {
        $notificationService = new FCMNotifications($this->di->get('config')->FCMApiKey);

        $payload = $this->request->getJsonRawBody();
        if (!($id = $payload->id) || ($pos = $this->playlist->getItemPosition($id)) === false)
            return $this->message('Item not found', 400);

        $item = $this->playlist->items[$pos];

        $data = [
            'youtubeId' => $id,
            'url' => $item->url,
            'volumeLevel' => $item->volumeLevel,
        ];

        if ($notificationService->sendPush($data, 'Play Now Video')) {
            return $this->message('Done!', 200);
        } else {
            return $this->message('Cannot send Push', 500);
        }
    }

    public function playNextAction()
    {
        $notificationService = new FCMNotifications($this->di->get('config')->FCMApiKey);

        $data = [
            'playNext' => true,
        ];

        if ($notificationService->sendPush($data, 'Play Next')) {
            return $this->message('Done!', 200);
        } else {
            return $this->message('Cannot send Push', 500);
        }
    }

}