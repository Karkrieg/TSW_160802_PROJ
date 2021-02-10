<?php
    class Group extends Database {
        // Parametry Bazy Danych
        //private $conn;
        private $table = 'groups';

        // Właściwości Grupy
        public $id;
        public $name;

        // Konstruktor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Metody 
        
        // Wczytaj wszystkie grupy
        public function read_all() 
        {
            $query =    'SELECT
                            g.id,
                            g.name
                        FROM
                            '.$this->table.' g
                        ORDER BY
                            g.id ASC';

            // Przygotowanie wyrażenia
            $statement = $this->conn->prepare($query);
        
            // Wykonanie 
            $statement->execute();
            
            return $statement;
        }

         // Wczytaj pojedynczą grupę
         public function read_single()
         {
             $query =    'SELECT
                             g.id,
                             g.name
                         FROM
                             '.$this->table.' g
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
             $this->name = $row['name'];
 
             return true;
             }
 
             return false;
         }

        // Dodanie grupy
        public function create()
        {
            // Query
            $query  =   'INSERT INTO '.$this->table.'
                        SET
                            name = :name;';
            
            // Prepare statement
            $statement = $this->conn->prepare($query);

            // Security (clean data)
            $this->name = htmlspecialchars(strip_tags($this->name));

            // Bind data
            $statement->bindParam(':name', $this->name);

            // Execute
            if($statement->execute()) {
                return true;
            }

            // Print error in case of error
            printf("Error: %s.\n", $statement->error);

            return false;
        }


        // Update grupy
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