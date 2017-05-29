<?php

use Phalcon\Mvc\Controller;

class ApiControllerBase extends Controller
{
    protected function _apiHeaders()
    {
        http_response_code(200);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, If-Modified-Since, Cache-Control, Pragma');
        header('Content-type: application/json');
    }

    /**
     * Shows response
     * @param mixed $data result
     */
    protected function _showResponse($data)
    {
        $this->_apiHeaders();
        echo json_encode($data);
        exit();
    }

    /**
     * Shows correct response
     * @param mixed $data
     * @return void
     */
    public function result($data)
    {
        $this->_showResponse(array(
            'result' => $data
        ));
    }

}