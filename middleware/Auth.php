<?php
    require __DIR__.'/../configuration/JwtHandler.php';
    //require __DIR__.'/../configuration/Database.php';
    //require __DIR__.'/../models/User.php';


    class Auth extends JwtHandler  {

        protected $db;
        protected $headers;
        protected $token;
        
        public function __construct($db,$headers) 
        {
            parent::__construct();
            $this->db = $db;
            $this->headers = $headers;
        }

        public function isAuthorised() {
            // Sprawdzenie, czy w nagłówkach istnieje nagłówek o nazwie 'Authorization', i czy nie jest on pusty
            if  (array_key_exists('Authorization',
                $this->headers) && !empty(trim($this->headers['Authorization']))){
                    // Rozdzielenie stringów oddzielonych spacjami
                    $this->token = explode(" ", trim($this->headers['Authorization']));
                    // Sprawdzenie, czy token jest ustawiony i różny od NULL i pojedynczej spacji
                    if  (isset($this->token[1]) && !empty(trim($this->token[1]))){
                        // Dekodowanie danych
                        $data = $this->jwt_decode_data($this->token[1]);
                        if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']){
                            // Pobranie danych użytkownika
                            //$user = $this->fetchUser($data['data']->user_id);
                            $user_data = "";
                            $user_data = CallAPI('GET','api/user/read_single.php?id='.$data['data']->user_id);
                            $user = [
                                'success' => 1,
                                'status' => 200,
                                'user' => json_decode($user_data)
                            ];
                            return $user;

                        } else return null;

                    }else return null;

                } else return null;

        }

        protected function fetchUser($user_id){
            try{
                $fetch_user_by_id = "SELECT `name`, `surname` FROM `users` WHERE `id`=:id";
                $query_stmt = $this->db->prepare($fetch_user_by_id);
                $query_stmt->bindValue(':id', $user_id,PDO::PARAM_INT);
                $query_stmt->execute();
    
                if($query_stmt->rowCount()):
                    $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                    return [
                        'success' => 1,
                        'status' => 200,
                        'user' => $row
                    ];
                else:
                    return null;
                endif;
            }
            catch(PDOException $e){
                return null;
            }
        }
    }
?>