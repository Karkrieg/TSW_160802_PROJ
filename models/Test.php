<?php
    class Test extends Database {
        // Parametry Bazy Danych
        //private $conn;
        private $table = 'tests';

        // Właściwości Testu
        public $id;
        public $gid;
        public $tytul;
        public $pytania;

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
                            t.id,
                            t.gid,
                            t.tytul,
                            t.pytania
                        FROM
                            '.$this->table.' t
                        ORDER BY
                            t.id ASC';

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
                            gid = :gid,
                            tytul = :tytul,
                            pytania = :pytania';
            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->gid = htmlspecialchars(strip_tags($this->gid));
            $this->tytul = htmlspecialchars(strip_tags($this->tytul));
            $this->pytania = $this->pytania;

            // Bind data
            $statement->bindParam(':gid', $this->gid);
            $statement->bindParam(':tytul', $this->tytul);
            $statement->bindParam(':pytania', $this->pytania);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }


        // Update testu
        public function update()
        {
            // Query
            $query  =   'UPDATE '.$this->table.'
                        SET
                            name = :name
                        WHERE 
                            id = :id';

            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->name = htmlspecialchars(strip_tags($this->name));

            // Bind data
            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':name', $this->name);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }

        // Usunięcie grupy
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