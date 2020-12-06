<?php

namespace Src\Controller;

use Src\TableGateways\DogsGateway;

class DogsController
{

    private $db;
    private $requestMethod;
    private $dogId;

    private $dogGateway;

    public function __construct($db, $requestMethod, $dogId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->dogId = $dogId;

        $this->dogGateway = new DogsGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->dogId) {
                    $response = $this->getDog($this->dogId);
                } else {
                    $response = $this->getAllDogs();
                };
                break;
            case 'POST':
                $response = $this->createDogFromRequest();
                break;
            case 'DELETE':
                $response = $this->deleteDog($this->dogId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllDogs()
    {
        $result = $this->dogGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getDog($id)
    {
        $result = $this->dogGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createDogFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateDog($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->dogGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function deleteDog($id)
    {
        $result = $this->dogGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->dogGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateDog($input)
    {
        if (!isset($input['name'])) {
            return false;
        }
        if (!isset($input['age'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
?>