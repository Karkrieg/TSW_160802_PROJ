<?php
    require __DIR__.'/../jwt/JWT.php';
    require __DIR__.'/../jwt/ExpiredException.php';
    require __DIR__.'/../jwt/SignatureInvalidException.php';
    require __DIR__.'/../jwt/BeforeValidException.php';

    use \Firebase\JWT\JWT;

    class JwtHandler {
        protected $jwt_secret;
        protected $token;
        protected $issuedAt;
        protected $expiresAt;
        protected $jwt;
        
        public function __construct()
        {
            // Ustawienie strefy czasowej
            date_default_timezone_set('Europe/Warsaw');
            // Ustawienie czasu wydania tokena
            $this->issuedAt = time();

            // Token traci ważność po 1 godzinie (3600 sekund)
            $this->expiresAt = $this->issuedAt + 3600;

            // Ustawienie sygnatury
            $this->jwt_secret = "SVN2IFGItdf6giNG5UGjhT2457ytsfbh5ft4hdth3fmjhm2fthn";
        }

        // Kodowanie tokena
        public function jwt_encode($iss, $data)
        {
            $this->token = array(
                // Dodanie identyfikatora
                "iss" => $iss,
                "aud" => $iss,
                // Data wydania tokena
                "iat" => $this->issuedAt,
                // Utrata ważności tokena
                "exp" => $this->expiresAt,
                // Payload
                "data" => $data
            );

            // Zakodowanie
            $this->jwt = JWT::encode($this->token, $this->jwt_secret);
            
            return $this->jwt;
        }

        protected function errMsg($msg) {
            return [
                "auth" => 0,
                "message" => $msg
            ];
        }

        // Dekodowanie tokena
        public function jwt_decode_data($jwt_token) {
            try {
                $decode = JWT::decode($jwt_token, $this->jwt_secret, array('HS256'));
                
                return [
                    "auth" => 1,
                    "data" => $decode->data
                ];
            } 
            catch(\Firebase\JWT\ExpiredException $e) {
                return $this->errMsg($e->getMessage());
            }
            catch(\Firebase\JWT\SignatureInvalidException $e) {
                return $this->errMsg($e->getMessage());
            }
            catch(\Firebase\JWT\BeforeValidException $e) {
                return $this->errMsg($e->getMessage());
            }
            catch(\DomainException $e) {
                return $this->errMsg($e->getMessage());
            }
            catch(\InvalidArgumentException $e) {
                return $this->errMsg($e->getMessage());
            }
            catch(\UnexpectedValueException $e) {
                return $this->errMsg($e->getMessage());
            }
        }
    }

?>