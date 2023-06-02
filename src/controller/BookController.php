<?php
namespace Src\Controller;

use Src\TableGateways\BookGateway;

class BookController {

    private $db;
    private $requestMethod;
    private $bookId;

    private $bookGateway;

    public function __construct($db, $requestMethod, $bookId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->bookId = $bookId;

        $this->bookGateway = new BookGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->bookId) {
                    $response = $this->getBook($this->bookId);
                } else {
                    $response = $this->getAllBooks();
                };
                break;
            case 'POST':
                $response = $this->checkoutBook();
                break;
            case 'PUT':
                $response = $this->returnBook($this->bookId);
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

    private function getAllBooks()
    {
        $result = $this->bookGateway->findAll();
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getBook($id)
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function checkoutBook()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateRequest($input)) {
            return $this->invalidRequest();
        }
        $this->bookGateway->insert($input);
        $response['statusCodeHeader'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }
    
    
    private function returnBook($id)
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateRequest($input)) {
            return $this->invalidRequest();
        }
        $this->bookGateway->update($id, $input);
        $response['statusCodeHeader'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }


    private function validateRequest($input)
    {
        if (! isset($input['bookId'])) {
            return false;
        }
        if (! isset($input['libraryId'])) {
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