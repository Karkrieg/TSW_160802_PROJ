<?php
    class Question extends Database {
        // Parametry Bazy Danych
        //private $conn;
        private $table = 'questions';

        // Właściwości Użytkownika
        public $id;
        public $question;

        // Konstruktor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Metody 
        
        // Wczytaj wszystkie pytania
        public function read_all() 
        {
            $query =    'SELECT
                            q.id,
                            q.question
                        FROM
                            '.$this->table.' q
                        ORDER BY
                            q.id ASC';

            // Przygotowanie wyrażenia
            $statement = $this->conn->prepare($query);
        
            // Wykonanie 
            $statement->execute();
            
            return $statement;
        }

        // Dodanie użytkownika
        public function create()
        {
            // Query
            $query  =   'INSERT INTO '.$this->table.'
                        SET
                            username = :username,
                            name = :name,
                            surname = :surname,
                            password = :password';
            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind data
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(':name', $this->name);
            $statement->bindParam(':surname', $this->surname);
            $statement->bindParam(':password', $this->password);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }


        // Update użytkownika
        public function update()
        {
            // Query
            $query  =   'UPDATE '.$this->table.'
                        SET
                            username = :username,
                            name = :name,
                            surname = :surname,
                            password = :password
                        WHERE 
                            id = :id';

            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind data
            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(':name', $this->name);
            $statement->bindParam(':surname', $this->surname);
            $statement->bindParam(':password', $this->password);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }

        // Usunięcie użytkownika
        public function delete()
        {
            // Query
            $query =   'DELETE FROM ' . $this->table .' WHERE id = :id';

            // Prepare statement
            $statement = $this->conn->prepare($query);
            
            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Powiązanie
            $statement->bindParam(':id', $this->id);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }

    }
?>