<?php
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class Usuario {
    private $secret_key = 'e0d17975bc9bd57eee132eecb6da6f11048e8a88506cc3bffc7249078cf2a77a';

    //Listar en el API
    public function index(){
        $clienteModel = new UserModel();
        $response = $clienteModel->all();

        if(isset($response) && !empty($response)){
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 400,
                'results' => "No hay registros"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));
    }

    public function get($id){
        $clienteModel = new UserModel();
        $response = $clienteModel->get($id);

        if(isset($response)){
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 404,
                'results' => "Cliente no encontrado"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));
    }

    public function create(){
        $inputJSON = file_get_contents('php://input');
        $object = json_decode($inputJSON);

        $clienteModel = new UserModel();
        $response = $clienteModel->create($object);

        if(isset($response) && !empty($response)){
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 400,
                'results' => "Cliente no creado"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));
    }

    public function update(){
        $inputJSON = file_get_contents('php://input');
        $object = json_decode($inputJSON);

        $clienteModel = new UserModel();
        $response = $clienteModel->update($object);

        if(isset($response) && !empty($response)){
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 400,
                'results' => "Cliente no actualizado"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));
    }

    public function login(){
        $inputJSON = file_get_contents('php://input');
        $object = json_decode($inputJSON);

        $clienteModel = new UserModel();
        $response = $clienteModel->login($object);

        if(isset($response) && !empty($response) && $response != false){
            // Datos que desea incluir en el token JWT
            $data = [
                'id' => $response->id,
                'nombre' => $response->nombre,
                'correo_electronico' => $response->correo_electronico,
                'rol' => $response->rol
            ];

            // Generar el token JWT 
            $jwt_token = JWT::encode($data, $this->secret_key, 'HS256');
            $json = array(
                'status' => 200,
                'results' => $jwt_token
            );
        } else {
            $json = array(
                'status' => 401,
                'results' => "Usuario no válido"
            );
        }
        echo json_encode($json, http_response_code($json["status"]));
    }

    public function authorize(){
        try {
            $token = null;
            $headers = apache_request_headers();

            if(isset($headers['Authentication'])){
                $matches = array();
                preg_match('/Bearer\s(\S+)/', $headers['Authentication'], $matches);
                if(isset($matches[1])){
                    $token = $matches[1];
                    return true;
                }
            } 
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
