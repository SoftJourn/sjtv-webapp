<?php


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
    }

    public function updateAction()
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
//            if($ext != '.gif') {
                $thumbnail = new \Phalcon\Image\Adapter\Imagick($path);
                $thumbnail->resize(320, 180);
                $thumbnail->save($thumbPath);
//            } else {
//                copy($path, $thumbPath);
//            }

            $image = new Image($fileName, $owner, $this->request->getPost('newImageDuration'));
            $this->playlist->addItem($image, true);
            $this->playlist->save();
            $result = [
                'status' => 'success',
                'message' => 'Item has been added!',
                'item' => $this->_renderItem($image)
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
        $video = new Youtube($url, $owner, $volumeLevel);
        if (!$video->id) {
            $result = [
                'status' => 'error',
                'message' => 'Video URL is invalid!'
            ];
            echo json_encode($result);
            return;
        }
        $this->playlist->addItem($video, true);
        $this->playlist->save();
        $result = [
            'status' => 'success',
            'message' => 'Item has been added!',
            'item' => $this->_renderItem($video)
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

    private function _renderItem($item)
    {
        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . "/views/partial/");
        $html = $view->render('item', ['item' => $item]);
        return $html;
    }
}

