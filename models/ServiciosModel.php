<?php
class ServiciosModel
{
    public $enlace;
    public function __construct()
    {
        $this->enlace = new MySqlConnect();
    }
    /*Listar */
    public function all()
    {
        try {
            //Consulta sql
            $vSql = "SELECT * FROM servicios;";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);

            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    /*Obtener */
    public function get($id)
    {
        try {
            //Consulta sql
            $vSql = "SELECT * FROM servicios where id=$id";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //obtener por plan
    
    public function getByPlan($plan_id)
    {
        try {
            // Consulta SQL utilizando JOIN con la tabla planes_servicios
            $vSql = "SELECT s.*  FROM servicios s INNER JOIN planes_servicios ps ON s.id = ps.servicio_id WHERE ps.plan_id = $plan_id";

            // Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);

            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create($object)
    {
        try {
            // Obtener los datos del objeto JSON
            $nombre = $object->nombre;
            $descripcion = $object->descripcion;
            $tipo = $object->tipo;
            $imagen_servicio = $object->imagen_servicio;

            // Consulta SQL para INSERT
            $vSql = "INSERT INTO servicios (nombre, descripcion, tipo, imagen_servicio) VALUES ('$nombre', '$descripcion', '$tipo', '$imagen_servicio')";

            // Ejecutar la consulta
            $this->enlace->ExecuteSQL($vSql);

            // Si es necesario, puedes realizar acciones adicionales aquí, como obtener el ID del nuevo servicio insertado.

            // Retornar la respuesta (opcional)
            return array("message" => "Registro insertado correctamente.");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update($object)
    {
        try {
            // Obtener los datos del objeto JSON
            $id = $object->id;
            $nombre = $object->nombre;
            $descripcion = $object->descripcion;
            $tipo = $object->tipo;
            $imagen_servicio = $object->imagen_servicio;

            // Verificar si se envió una nueva imagen
            if (!empty($imagen_servicio)) {
                // Consulta SQL para UPDATE con nueva imagen
                $vSql = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', tipo = '$tipo', imagen_servicio = '$imagen_servicio' WHERE id = $id";
            } else {
                // Consulta SQL para UPDATE sin modificar la imagen
                $vSql = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', tipo = '$tipo' WHERE id = $id";
            }

            // Ejecutar la consulta
            $this->enlace->ExecuteSQL($vSql);

            // Si es necesario, puedes realizar acciones adicionales aquí.

            // Retornar la respuesta (opcional)
            return array("message" => "Registro actualizado correctamente.");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



}
