<?php
    class Database {
        // Parametry bazy danych
        private $host       = 'localhost';
        private $dbname     = 'projekttsw_test';
        private $username   = 'root';
        private $password   = '';
        private $conn;

        // Połączenie z bazą danych
        public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname= ' . $this->dbname,
            $this->username,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) {
            echo 'Błąd połączenia z bazą danych: '.$e->getMessage();
        }
        return $this->conn;
    }
    }

    
?>