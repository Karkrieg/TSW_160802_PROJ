<?php
class Test extends Database
{
    // Parametry Bazy Danych
    //private $conn;
    private $table = 'tests';

    // Właściwości Testu
    public $id;
    public $gid;
    public $tytul;
    public $dane;

    // Konstruktor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Metody 

    // Wczytaj wszystkie testy
    public function read_all()
    {
        $query =    'SELECT
                            t.id,
                            t.gid,
                            g.name as grupa,
                            t.tytul,
                            t.dane
                        FROM
                            ' . $this->table . ' t
                        LEFT JOIN groups g ON g.id = t.gid
                        ORDER BY
                            t.id ASC';

        // Przygotowanie wyrażenia
        $statement = $this->conn->prepare($query);

        // Wykonanie 
        $statement->execute();

        return $statement;
    }

    // Wczytaj pojedynczy test
    public function read_single()
    {
        $query =    'SELECT
                            t.id,
                            t.gid,
                            t.tytul,
                            t.dane
                        FROM
                            ' . $this->table . ' t
                        WHERE
                            t.id = ?
                        LIMIT 1';

        // Przygotowanie wyrażenia
        $statement = $this->conn->prepare($query);

        // Bind id
        $statement->bindParam(1, $this->id);

        // Wykonanie
        if ($statement->execute()) {

            // Jeśli istnieje test o podanym id
            if ($statement->rowCount()) {
                $row = $statement->fetch(PDO::FETCH_ASSOC);

                // Właściwości
                $this->id = $row['id'];
                $this->gid = $row['gid'];
                $this->tytul = $row['tytul'];
                $this->dane = $row['dane'];

                return true;
            }
        }
        return false;
    }

    // Dodanie użytkownika
    public function create()
    {
        // Query
        $query  =   'INSERT INTO ' . $this->table . '
                        SET
                            gid = :gid,
                            tytul = :tytul,
                            dane = :dane';

        // Prepare statement
        $statement = $this->conn->prepare($query);

        // Security (clean data)
        $this->gid = htmlspecialchars(strip_tags($this->gid));
        $this->tytul = htmlspecialchars(strip_tags($this->tytul));
        $this->dane = $this->dane;

        // Bind data
        $statement->bindParam(':gid', $this->gid);
        $statement->bindParam(':tytul', $this->tytul);
        $statement->bindParam(':dane', $this->dane);

        // Execute
        if ($statement->execute()) {
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
        $query  =   'UPDATE ' . $this->table . '
                        SET
                            gid = :gid,
                            tytul = :tytul,
                            dane = :dane
                        WHERE 
                            id = :id';


        // Prepare statement
        $statement = $this->conn->prepare($query);

        // Security (clean data)
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->gid = htmlspecialchars(strip_tags($this->gid));
        $this->tytul = htmlspecialchars(strip_tags($this->tytul));
        $this->dane = $this->dane;

        // Bind data
        $statement->bindParam(':id', $this->id);
        $statement->bindParam(':gid', $this->gid);
        $statement->bindParam(':tytul', $this->tytul);
        $statement->bindParam(':dane', $this->dane);

        // Execute
        if ($statement->execute()) {
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
        $query =   'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $statement = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Powiązanie
        $statement->bindParam(':id', $this->id);

        // Execute
        if ($statement->execute()) {
            return true;
        }

        // Print error in case of error
        printf("Error: %s.\n", $statement->error);

        return false;
    }
}

?>