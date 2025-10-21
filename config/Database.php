<?php
// Fichier: config/Database.php

class Database {
    
    // Ma3lomat dyal l-Database (Standard dyal XAMPP)
    private $host = "localhost";
    private $db_name = "mecalinks_db"; // Ism l-DB li 3ad saybti
    private $username = "root";
    private $password = "";
    public $conn;

    // L-Method li katjib l-connexion
    public function getConnection(){
    
        $this->conn = null; // Nbdaw b-connexion khawya

        try {
            // Ghadi n7awlo n-connectaw l-DB b-PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            
            // N-setiw l-mode dyal l-errors bach ybiyn lina ayi mochkil
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception){
            // Ila wqe3 chi mochkil (e.g., password ghalat, DB ma kaynach)
            echo "Mochkil f-l-Connexion: " . $exception->getMessage();
        }

        // N-rj3o l-connexion l-li 3eyyet lina
        return $this->conn;
    }
}
?>