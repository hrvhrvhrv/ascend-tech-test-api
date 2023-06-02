<?php
namespace Src\Controller;

use Src\TableGateways\UserGateway;

class UserController {

    private $db;
    private $requestMethod;
    private $userId;

    private $userGateway;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;

        $this->userGateway = new UserGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getUser($this->userId);
                break;
            case 'POST':
                $response = $this->createUser();                
                break;

            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['statusCodeHeader']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    
    private function getUser($id)
    {
        $result = $this->userGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createUser()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateRequest($input)) {
            return $this->invalidRequest();
        }
        $this->userGateway->insert($input);
        $response['statusCodeHeader'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function changeUserDetails($id)
    {
        $result = $this->userGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateRequest($input)) {
            return $this->invalidRequest();
        }
        $this->userGateway->update($id, $input);
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteUser($id)
    {
        $result = $this->userGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->userGateway->delete($id);
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateRequest($input)
    {
        if (! isset($input['userId'])) {
            return false;
        }
        return true;
    }

    private function invalidRequest()
    {
        $response['statusCodeHeader'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['statusCodeHeader'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}