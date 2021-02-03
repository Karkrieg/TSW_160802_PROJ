<?php
    class User extends Database {
        // Parametry Bazy Danych
        //private $conn;
        private $table = 'users';

        // Właściwości Użytkownika
        public $id;
        public $group_id;
        public $name;
        public $surname;
        public $index_number;
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
                            u.username,
                            u.name,
                            u.surname
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
                            u.username,
                            u.name,
                            u.surname
                        FROM
                            '.$this->table.' u
                        WHERE
                            u.id = ?';

            // Przygotowanie wyrażenia
            $statement = $this->conn->prepare($query);

            // Bind id
            $statement->bindParam(1, $this->id);

            // Wykonanie
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            // Właściwości
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->name = $row['name'];
            $this->surname = $row['surname'];
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
    }
?>