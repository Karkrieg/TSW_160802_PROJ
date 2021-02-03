<?php
    class Database {
        // Parametry bazy danych
        private $dbHost       = 'localhost';
        private $dbName     = 'projekttsw_test';
        private $dbUser   = 'root';
        private $dbPassword   = '';
        private $conn;

        // Połączenie z bazą danych
        public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->dbHost . '; dbname='.$this->dbName,
            $this->dbUser,$this->dbPassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            echo 'Błąd połączenia z bazą danych: '.$e->getMessage();
        }
        return $this->conn;
    }
    }

    
?>