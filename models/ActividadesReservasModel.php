<?php
class ActividadesReservasModel
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
            $vSql = "SELECT r.*, CONCAT(c.nombre, ' ', c.apellidos) AS nombre_cliente, s.nombre AS nombre_servicio FROM reservas r JOIN actividades_grupales ag ON r.actividad_grupal_id = ag.id JOIN clientes c ON r.cliente_id = c.id JOIN servicios s ON ag.servicio_id = s.id;";

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
            // Consulta sql
            $vSql = "SELECT * FROM reservas where id=$id;";
            // Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            // Retornar el objeto
            return $vResultado[0];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create($objeto)
    {
        try {
            //Consulta SQL para insertar el ejercicio
            $vSql = "INSERT INTO `gimnasio`.`reservas` (`actividad_grupal_id`,`cliente_id`) VALUES($objeto->actividad_id,$objeto->cliente_id);";
            $vResultado = $this->enlace->executeSQL_DML_last($vSql);
            // Retornar el objeto creado
            return $this->get($vResultado);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



    public function update($objeto)
    {
        try {
            //Consulta sql
            $vSql = "UPDATE `gimnasio`.`reservas` SET `actividad_grupal_id` = $objeto->actividad_grupal_id , `cliente_id` = $objeto->cliente_id , `estado` = $objeto->estado WHERE `id` = $objeto->id;";
            //Ejecutar la consulta
            $vResultado = $this->enlace->executeSQL_DML($vSql);
            // Retornar el objeto actualizado
            return $this->get($objeto->id);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    public function getInscritoByCliente($clienteId)
    {
        try {
            // Consulta SQL para obtener las actividades grupales inscritas por el cliente
            $vSql = "SELECT a.*, r.estado, s.nombre AS servicio_nombre FROM reservas r INNER JOIN actividades_grupales a ON r.actividad_grupal_id = a.id INNER JOIN servicios s ON a.servicio_id = s.id WHERE r.cliente_id = $clienteId;";
    
            // Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
    
            // Retornar el resultado
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function updateEstadoReservas($reserva)
    {
        try {
            // Consulta SQL para obtener las actividades grupales inscritas por el cliente
            $vSql = "UPDATE `gimnasio`.`reservas` SET `estado` = 'Inactivo' WHERE `actividad_grupal_id` = $reserva;";
    
            // Ejecutar la consulta
             $this->enlace->ExecuteSQL($vSql);
    

            // Retornar el resultado
            return $this->$vResultado=['Estado actualizado con exito'];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


}
