<?php
class Answer extends Database
{
    // Parametry Bazy Danych
    //private $conn;
    private $table = 'answers';

    // Właściwości odpowiedzi
    public $id;
    public $tid;
    public $gid;
    public $uid;
    public $tytul;
    public $start;
    public $stop;
    public $points;
    public $dane;

    // Konstruktor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Metody 

    // Wczytaj wszystkie odpowiedzi
    public function read_all()
    {
        $query =    'SELECT
                            a.id,
                            a.tid,
                            a.gid,
                            a.uid;
                            t.tytul as tytul,
                            g.name as grupa,
                            u.username as username,
                            a.tytul,
                            a.start,
                            a.stop,
                            a.dane
                        FROM
                            ' . $this->table . ' a
                        LEFT JOIN groups g ON g.id = a.gid
                        LEFT JOIN tests t ON t.id = a.tid
                        LEFT JOIN users u ON u.id = a.uid
                        ORDER BY
                            a.stop DESC';

        // Przygotowanie wyrażenia
        $statement = $this->conn->prepare($query);

        // Wykonanie 
        $statement->execute();

        return $statement;
    }

    // Wczytaj pojedynczą odpowiedź
    public function read_single()
    {
        $query =    'SELECT
                            a.id,
                            a.tid,
                            a.gid,
                            a.uid;
                            t.tytul as tytul,
                            g.name as grupa,
                            u.username as username,
                            a.start,
                            a.stop,
                            a.dane
                        FROM
                            ' . $this->table . ' a
                            LEFT JOIN groups g ON g.id = a.gid
                            LEFT JOIN tests t ON t.id = a.tid
                            LEFT JOIN users u ON u.id = a.uid
                        WHERE
                            a.id = ?
                        LIMIT 1';

        // Przygotowanie wyrażenia
        $statement = $this->conn->prepare($query);

        // Bind id
        $statement->bindParam(1, $this->id);

        // Wykonanie
        if ($statement->execute()) {

            // Jeśli istnieje odpowiedź o podanym id
            if ($statement->rowCount()) {
                $row = $statement->fetch(PDO::FETCH_ASSOC);

                // Właściwości
                $this->id = $row['id'];
                $this->tid = $row['tid'];
                $this->gid = $row['gid'];
                $this->uid = $row['uid'];
                $this->start = $row['start'];
                $this->dane = $row['dane'];

                return true;
            }
        }
        return false;
    }

    // Dodanie odpowiedzi
    public function create()
    {
        // Query
        $query  =   'INSERT INTO ' . $this->table . '
                        SET
                            tid = :tid,
                            gid = :gid,
                            uid = :uid,
                            start = :start,
                            dane = :dane';

        // Prepare statement
        $statement = $this->conn->prepare($query);

        // Security (clean data)
        $this->tid = htmlspecialchars(strip_tags($this->tid));
        $this->gid = htmlspecialchars(strip_tags($this->gid));
        $this->uid = htmlspecialchars(strip_tags($this->uid));
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->dane = $this->dane;

        // Bind data
        $statement->bindParam(':tid', $this->tid);
        $statement->bindParam(':gid', $this->gid);
        $statement->bindParam(':uid', $this->uid);
        $statement->bindParam(':start', $this->start);
        $statement->bindParam(':dane', $this->dane);

        // Execute
        if ($statement->execute()) {
            return true;
        }

        // Print error in case of error
        printf("Error: %s.\n", $statement->error);

        return false;
    }


    // Update odpowiedzi
    public function update()
    {
        // Query
        $query  =   'UPDATE ' . $this->table . '
                        SET
                            tid = :tid,
                            gid = :gid,
                            uid = :uid,
                            start = :start,
                            dane = :dane
                        WHERE 
                            id = :id';


        // Prepare statement
        $statement = $this->conn->prepare($query);

        // Security (clean data)
        $this->tid = htmlspecialchars(strip_tags($this->tid));
        $this->gid = htmlspecialchars(strip_tags($this->gid));
        $this->uid = htmlspecialchars(strip_tags($this->uid));
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->dane = $this->dane;

        // Bind data
        $statement->bindParam(':tid', $this->tid);
        $statement->bindParam(':gid', $this->gid);
        $statement->bindParam(':uid', $this->uid);
        $statement->bindParam(':start', $this->start);
        $statement->bindParam(':dane', $this->dane);

        // Execute
        if ($statement->execute()) {
            return true;
        }

        // Print error in case of error
        printf("Error: %s.\n", $statement->error);

        return false;
    }

    // Usunięcie odpowiedzi
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