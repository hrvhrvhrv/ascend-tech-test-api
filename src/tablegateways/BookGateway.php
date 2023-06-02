<?php
namespace Src\TableGateways;

class BookGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = <<<SQL
            SELECT 
                `id`,
                `bookName`,
                `authorFirstName`,
                `authorLastName`,
                `publishedYear`,
                `description`,
                `imageRef`
            FROM
                `books`
        SQL;

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find(int $id)
    {
        $statement = <<<SQL
            SELECT 
                `id`,
                `bookName`,
                `authorFirstName`,
                `authorLastName`,
                `publishedYear`,
                `description`,
                `imageRef`
            FROM
                `books`
            WHERE 
                `id` = ?;
            SQL;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    
    public function numberOfCopiesAvailable(int $bookId) :int
    {
        $statement = <<<SQL
            SELECT 
                `id`,
                `bookName`,
                `authorFirstName`,
                `authorLastName`,
                `publishedYear`,
                `description`,
                `imageRef`
            FROM
                `books`
        SQL;

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = <<<SQL
            INSERT INTO `books` 
                (
                    `bookName`,
                    `authorFirstName`,
                    `authorLastName`,
                    `publishedYear`,
                    `description`,
                    `imageRef`
                )
            VALUES
                (   :bookName,
                    :authorFirstName,
                    :authorLastName,
                    :publishedYear,
                    :description,
                    :imageRef
                );
            SQL;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'bookName' => $input['bookName'],
                'authorFirstName' => $input['authorFirstName'],
                'authorLastName' => $input['authorLastName'],
                'publishedYear' => $input['publishedYear'],
                'description' => $input['description'],
                'imageRef' => $input['imageRef'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}