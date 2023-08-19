<?php
class HistorialRutinasModel
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
            $vSql = "SELECT hr.*, r.nombre AS nombre_rutina, CONCAT(c.nombre, ' ', c.apellidos) AS nombre_cliente FROM historial_rutinas hr JOIN rutinas r ON hr.id_rutina = r.id JOIN clientes c ON hr.id_cliente = c.id;";

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
            $vSql = "SELECT hr.*, r.nombre AS nombre_rutina, CONCAT(c.nombre, ' ', c.apellidos) AS nombre_cliente FROM historial_rutinas hr JOIN rutinas r ON hr.id_rutina = r.id JOIN clientes c ON hr.id_cliente = c.id WHERE hr.id = $id;";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function getbyCliente($idCliente)
    {
        try {
            //Consulta sql
            $vSql = "SELECT hr.*, r.nombre AS nombre_rutina, CONCAT(c.nombre, ' ', c.apellidos) AS nombre_cliente FROM historial_rutinas hr JOIN rutinas r ON hr.id_rutina = r.id JOIN clientes c ON hr.id_cliente = c.id WHERE hr.id_cliente = $idCliente and hr.estado='Activo';";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create($objeto)
    {
        try {
            // Consulta SQL para insertar la rutina
            $vSql = "INSERT INTO historial_rutinas (id_rutina, id_cliente) VALUES ('$objeto->id_rutina', '$objeto->id_cliente')";
            $vResultado = $this->enlace->executeSQL_DML_last($vSql);

            // Obtener el ID de la rutina recién creada
            $idreserva= $vResultado;

            // Retornar el objeto creado
            return ['Se agreggo correctamente la rutina al historial'];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update($objeto)
    {
        try {
            // Consulta sql para actualizar la rutina
            $vSql = "UPDATE historial_rutinas SET estado = '$objeto->estado' where id=$objeto->id";
            // Ejecutar la consulta
            $vResultado = $this->enlace->executeSQL_DML($vSql);
            return ['Actualizado con exito'];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
