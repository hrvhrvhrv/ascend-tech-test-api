<?php
namespace Src\TableGateways;

class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
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

    public function insertNewUser(Array $input)
    {
        $statement = <<<SQL
            INSERT INTO `users` 
                (
                    
                )
            VALUES
                (   
                    :bookName,
                );
            SQL;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'bookName' => $input['bookName'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

}