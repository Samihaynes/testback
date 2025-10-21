<?php
// Fichier: endpoints/auth.php

// 1. Headers dyal l-API (DARORI)
header("Access-Control-Allow-Origin: *"); // Kaykhlli React ydwi m3a had l-API
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Ghir POST w OPTIONS li msmou7 lihom
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. N-handleiw l-OPTIONS request (li kayji 9bel l-POST)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Njbdo l-Database w l-data li jat
include_once '../config/Database.php';

// N-connectaw l-DB
$database = new Database();
$db = $database->getConnection();

// Nqraw l-data li jat men React (kayji b-format JSON)
$data = json_decode(file_get_contents("php://input"));

// 4. Nchofo chno bgha l-user (Register wla Login)
// Ghadi ntfarqo binathom b-wa7ed l-parametre f-l-URL
// Masalan: auth.php?action=register awla auth.php?action=login
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    // ===================================
    // L-7ala 1: L-User bgha y-REGISTER
    // ===================================
    case 'register':
        // N-vérifiw wach l-data kamla wasla
        if (
            !empty($data->nombre_usuario) &&
            !empty($data->email) &&
            !empty($data->password)
        ) {
            // N-hashiw (n-codiw) l-password 3la wed l-sécurité
            $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

            $query = "INSERT INTO Usuarios (nombre_usuario, email, password_hash, rol) 
                      VALUES (:nombre, :email, :pass, 'usuario')";
            
            $stmt = $db->prepare($query);

            // N-bindiw (nrbto) l-data
            $stmt->bindParam(':nombre', $data->nombre_usuario);
            $stmt->bindParam(':email', $data->email);
            $stmt->bindParam(':pass', $password_hash);

            if ($stmt->execute()) {
                http_response_code(201); // 201 = Created
                echo json_encode(array("message" => "L-User t-crea b-naja7."));
            } else {
                http_response_code(503); // 503 = Service Unavailable
                echo json_encode(array("message" => "Ma qdernach n-creeiw l-user."));
            }
        } else {
            http_response_code(400); // 400 = Bad Request
            echo json_encode(array("message" => "Data naqsa."));
        }
        break;

    // ===================================
    // L-7ala 2: L-User bgha y-LOGIN
    // ===================================
    case 'login':
        // N-vérifiw wach l-data kamla wasla
        if (!empty($data->email) && !empty($data->password)) {
            
            $query = "SELECT id_usuario, nombre_usuario, email, password_hash, rol 
                      FROM Usuarios WHERE email = :email LIMIT 1";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $data->email);
            $stmt->execute();

            $num = $stmt->rowCount(); // Ch7al men user lqina (khasso ykon 1)

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC); // Njbdo l-data dyal l-user
                
                $id_usuario = $row['id_usuario'];
                $nombre_usuario = $row['nombre_usuario'];
                $password_hash_db = $row['password_hash'];
                $rol = $row['rol'];

                // DABA L-MOCHTRIL L-KBIR: N-vérifiw l-password
                if (password_verify($data->password, $password_hash_db)) {
                    
                    // L-Password S7I7!
                    // (Hna normalment khassk t-creer wa7ed l-TOKEN (JWT) w tsifto)
                    // Daba, gha nsifto ghir message dyal naja7 w l-ma3lomat dyal l-user
                    
                    http_response_code(200); // OK
                    echo json_encode(array(
                        "message" => "Login s7i7.",
                        "user" => array(
                            "id" => $id_usuario,
                            "nombre" => $nombre_usuario,
                            "email" => $data->email,
                            "rol" => $rol
                        )
                        // "jwt_token" => $token_li_gha_tcreer -- (khalliwha 7tal men b3d)
                    ));

                } else {
                    // L-Password GHALAT
                    http_response_code(401); // Unauthorized
                    echo json_encode(array("message" => "L-Password ghalat."));
                }
            } else {
                // Ma lqinach l-Email
                http_response_code(404); // Not Found
                echo json_encode(array("message" => "Hada l-email ma kaynch."));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Data naqsa."));
        }
        break;

    // ===================================
    // L-7ala 3: Ma 3refnach chno bgha
    // ===================================
    default:
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "L-Action '" . $action . "' ma 3rfnahach."));
        break;
}
?>