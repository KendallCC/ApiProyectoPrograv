<?php
//class Actor
class Servicios
{
    //Listar en el API
    public function index()
    {
        //Obtener el listado del Modelo
        $servicios = new serviciosModel();
        $response = $servicios->all();
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

        $servicios = new serviciosModel();
        $response = $servicios->get($param);
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

    public function create( ){
        $inputJSON=file_get_contents('php://input');
        $object = json_decode($inputJSON); 
        $Servicios=new ServiciosModel();
        $response=$Servicios->create($object);
        if(isset($response) && !empty($response)){
            $json=array(
                'status'=>200,
                'results'=>$response[0]
            );
        }else{
            $json=array(
                'status'=>400,
                'total'=>0,
                'results'=>"No hay registros"
            );
        }
        echo json_encode($json,
        http_response_code($json["status"]));
        
    }
    public function update(){
        $inputJSON=file_get_contents('php://input');
        $object = json_decode($inputJSON); 
        $Servicios=new ServiciosModel();
        $response=$Servicios->update($object);
        if(isset($response) && !empty($response)){
            $json=array(
                'status'=>200,
                'total'=>count($response),
                'results'=>$response[0]
            );
        }else{
            $json=array(
                'status'=>400,
                'total'=>0,
                'results'=>"No hay registros"
            );
        }
        echo json_encode($json,
        http_response_code($json["status"]));
        
    }
}
