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

    function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

    
?>