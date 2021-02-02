<?php
    class User {
        // Parametry Bazy Danych
        private $conn;
        private $table = '';

        // Właściwości Użytkownika
        public $id;
        public $group_id;
        public $name;
        public $surname;
        public $index_number;
        public $created_at;

        // Konstruktor
        public function __construct($dbo)
        {
            $this->conn = $dbo;
        }

        // Metody GET
        public function get() {
            $query =    'SELECT
                            u.id,
                            u.group
                        FROM
                            Users u
                        ORDER BY
                            u.id;';

        }
    }
?>