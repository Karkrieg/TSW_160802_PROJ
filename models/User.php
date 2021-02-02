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

        // Metody GET
        public function read() {
            $query =    'SELECT
                            u.id,
                            u.username,
                            u.name,
                            u.surname
                        FROM
                            '.$this->table.' u
                        ORDER BY
                            u.id ASC';
        }
    }
?>