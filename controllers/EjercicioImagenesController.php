<?php
//class Actor
class ImagenesEjercicios
{
    //Listar en el API
    public function index()
    {
        //Obtener el listado del Modelo
        $Ejercicio = new EjerciciosImagenesModel();
        $response = $Ejercicio->all();
        //Si hay respuesta
        if (isset($response) && !empty($response)) {
            //Armar el json
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
        echo json_encode(
            $json,
            http_response_code($json["status"])
        );
    }
    public function get($param)
    {

        $Ejercicios = new EjerciciosImagenesModel();
        $response = $Ejercicios->get($param);
        $json = array(
            'status' => 200,
            'total' => count($response),
            'results' => $response
        );
        if (isset($response) && !empty($response)) {
            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {
            $json = array(
                'status' => 400,
                'results' => "No existe el actor"
            );
        }
        echo json_encode(
            $json,
            http_response_code($json["status"])
        );
    }
}
