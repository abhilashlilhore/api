<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'blog';
    private $username = 'root'; 
    private $password = ''; 
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            
            echo 'Connection Error: ' . $e->getMessage();
            exit();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
