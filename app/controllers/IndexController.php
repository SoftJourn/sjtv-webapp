<?php

use App\Models\Playlist,
    App\Models\Image,
    App\Models\Youtube,
    App\Plugins\FCMNotifications,
    App\Models\Facebook\Post;

class IndexController extends ControllerBase
{
    /** @var  Playlist */
    public $playlist;

    public function onConstruct()
    {
        $this->playlist = new Playlist();
    }

    public function indexAction()
    {
        $this->view->order = $this->playlist->order;
        $this->view->defaultDuration = $this->playlist->defaultDuration;
        $this->view->items = $this->playlist->items;
        $this->view->login = $this->session->get('user')->login;
        $this->view->status = $this->request->get('status') ?? 'enabled';
    }

    public function updateConfigAction()
    {
        if ($this->playlist->update($_POST)) {
            $result = [
                'status' => 'success',
                'message' => 'Playlist settings have been updated'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Playlist settings update failed'
            ];
        }
        echo json_encode($result);
    }


    public function addImageAction()
    {
        $owner = $this->session->get('user')->login;
        if (!$this->request->hasFiles()) {
            $result = [
                'status' => 'error',
                'message' => 'Image was not specified'
            ];
            echo json_encode($result);
            return;
        }

        /** @var \Phalcon\Http\Request\File $file */
        foreach ($this->request->getUploadedFiles() as $file) {
            $ext = Image::$supportedTypes[$file->getRealType()];
            if (!$ext) {
                $result = [
                    'status' => 'error',
                    'message' => 'Image type is not supported'
                ];
                echo json_encode($result);
                return;
            }
            $fileName = $owner . '-' . time() . $ext;
            $path = $this->di->get('config')->application->publicDir . Image::$filesDir . $fileName;
            $thumbPath = $this->di->get('config')->application->publicDir . Image::$filesDir . 'thumbs/' . $fileName;
            $file->moveTo($path);
            if ($ext != '.gif') {
                $thumbnail = new \Phalcon\Image\Adapter\Imagick($path);
                $thumbnail->resize(320, 180);
                $thumbnail->save($thumbPath);
            } else {
                copy($path, $thumbPath);
            }

            $enabled = boolval(strtolower($this->request->getPost('enabled')) == 'on');

            $image = new Image($fileName, $owner, $enabled, $this->request->getPost('newImageDuration'));
            $image->setDateAndTime($this->request->getPost());
            $this->playlist->addItem($image, true);
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been added!',
                'item' => $this->_renderItem($image),
                'itemData' => $image
            ];
        }
        echo json_encode($result);
    }

    public function addYoutubeAction()
    {
        $owner = $this->session->get('user')->login;
        $url = $this->request->getPost('youtubeURL');
        $volumeLevel = $this->request->getPost('volumeLevel');
        if (!$url) {
            $result = [
                'status' => 'error',
                'message' => 'Video URL is required!'
            ];
            echo json_encode($result);
            return;
        }

        $enabled = (strtolower($this->request->getPost('enabled')) == 'on');

        $video = new Youtube($url, $owner, $enabled, $volumeLevel);
        if (!$video->id) {
            $result = [
                'status' => 'error',
                'message' => 'Video URL is invalid!'
            ];
            echo json_encode($result);
            return;
        }
        $video->setDateAndTime($this->request->getPost());
        $this->playlist->addItem($video, true);
        $this->playlist->save();
        $result = [
            'status' => 'success',
            'message' => 'Item has been added!',
            'item' => $this->_renderItem($video),
            'itemData' => $video
        ];
        echo json_encode($result);
    }

    public function addFacebookPostAction()
    {
        $owner = $this->session->get('user')->login;
        $url = $this->request->getPost('url');
        $duration = $this->request->getPost('newImageDuration');

        $enabled = (strtolower($this->request->getPost('enabled')) == 'on');

        $facebookPost = new Post($url, $owner, $enabled, $duration);
        if (!$facebookPost->id) {
            $result = [
                'status' => 'error',
                'message' => 'Video URL is invalid!'
            ];
            echo json_encode($result);
            return;
        }
        $facebookPost->setDateAndTime($this->request->getPost());
        $this->playlist->addItem($facebookPost, true);
        $this->playlist->save();
        $result = [
            'status' => 'success',
            'message' => 'Item has been added!',
            'item' => $this->_renderItem($facebookPost),
            'itemData' => $facebookPost
        ];
        echo json_encode($result);
    }

    public function updateItemAction($id)
    {
        if (!$id || !$this->request->isPost()) {
            return false;
        }
        if ($this->playlist->updateItem($id, $_POST)) {
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been updated!'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Item not found!'
            ];
        }
        echo json_encode($result);
    }

    public function removeItemAction($id)
    {
        if ($this->playlist->removeItem($id)) {
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been removed'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Item not found'
            ];
        }
        echo json_encode($result);
    }

    public function enableItemAction($id)
    {
        if ($this->playlist->updateItem($id, ['enabled' => true])) {
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been enabled'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Item not found'
            ];
        }
        echo json_encode($result);
    }

    public function disableItemAction($id)
    {
        if ($this->playlist->updateItem($id, ['enabled' => false])) {
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been disabled'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Item not found'
            ];
        }
        echo json_encode($result);
    }

    public function playNowAction()
    {
        $notificationService = new FCMNotifications($this->di->get('config')->FCMApiKey);

        $data = [
            'youtubeId' => $this->request->get('id'),
            'url' => 'https://www.youtube.com/watch?v=' . $this->request->get('id'),
            'volumeLevel' => $this->request->get('volume'),
        ];

        if($res = $notificationService->sendPush($data, 'Play Now Video')) {
            $result = [
                'status' => 'success',
                'message' => 'Video is playing'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Cannot send Push'
            ];
        }
        echo json_encode($result);
    }

    public function  playNextAction()
    {
        $notificationService = new FCMNotifications($this->di->get('config')->FCMApiKey);

        $data = [
            'playNext' => true,
        ];

        if($res = $notificationService->sendPush($data, 'Play Next')) {
            $result = [
                'status' => 'success',
                'message' => 'Done!'
            ];
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Cannot send Push'
            ];
        }
        echo json_encode($result);
    }

    /**
     * @param $id
     * @param $like
     * @return Phalcon\Http\Response
     */

    public function ratingAction($id, $like){

        if (!$id)
            return false;

        $pos = $this->playlist->getItemPosition($id);

        if (is_null($pos))
            return false;

        $item = $this->playlist->items[$pos];

        ($like) ? $item->likes++ : $item->dislikes++;

        if ($this->playlist->updateItem($id, $item)) {
            $this->playlist->save();
            $result = $item;
        } else {
            $result = [
                'status' => 'error',
                'message' => 'Item not found!'
            ];
        }

        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($result));

        return $this->response;

    }

    private function _renderItem($item)
    {
        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . "/views/partial/");
        $status = $this->request->get('status') ?? 'enabled';
        $html = $view->render('item', ['item' => $item, 'status' => $status]);
        return $html;
    }
}

