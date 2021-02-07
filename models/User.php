<?php
    class User extends Database {
        // Parametry Bazy Danych
        private $table = 'users';

        // Właściwości Użytkownika
        public $id;
        public $grupa;
        public $name;
        public $surname;
        public $email;
        public $created_at;

        // Konstruktor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Metody 
        
        // Wczytaj wszystkich użytkowników
        public function read_all() 
        {
            $query =    'SELECT
                            u.id,
                            u.grupa,
                            u.username,
                            u.name,
                            u.surname,
                            u.email
                        FROM
                            '.$this->table.' u
                        ORDER BY
                            u.id ASC';

            // Przygotowanie wyrażenia
            $statement = $this->conn->prepare($query);
        
            // Wykonanie 
            $statement->execute();
            
            return $statement;
        }

        // Wczytaj pojedynczego użytkownika
        public function read_single()
        {
            $query =    'SELECT
                            u.id,
                            u.grupa,
                            u.username,
                            u.name,
                            u.surname,
                            u.email
                        FROM
                            '.$this->table.' u
                        WHERE
                            u.id = ?
                        LIMIT 1';

            // Przygotowanie wyrażenia
            $statement = $this->conn->prepare($query);

            // Bind id
            $statement->bindParam(1, $this->id);

            // Wykonanie
            if($statement->execute()){

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            // Właściwości
            $this->id = $row['id'];
            $this->grupa = $row['grupa'];
            $this->username = $row['username'];
            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->email = $row['email'];

            return true;
            }

            return false;
        }

        // Dodanie użytkownika
        public function create()
        {
            // Query
            $query  =   'INSERT INTO '.$this->table.'
                        SET
                            grupa = :grupa,
                            username = :username,
                            name = :name,
                            surname = :surname,
                            email = :email,
                            password = :password';
            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->grupa = htmlspecialchars(strip_tags($this->grupa));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind data
            $statement->bindParam(':grupa', $this->grupa);
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(':name', $this->name);
            $statement->bindParam(':surname', $this->surname);
            $statement->bindParam(':email', $this->email);
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
                            grupa = :grupa,
                            username = :username,
                            name = :name,
                            surname = :surname,
                            email = :email,
                            password = :password
                        WHERE 
                            id = :id';

            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->grupa = htmlspecialchars(strip_tags($this->grupa));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            // Bind data
            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':grupa', $this->grupa);
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(':name', $this->name);
            $statement->bindParam(':surname', $this->surname);
            $statement->bindParam(':email', $this->email);
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