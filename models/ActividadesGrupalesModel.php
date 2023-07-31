<?php
class ActividadesGrupalesModel
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
            $vSql = "SELECT ag.id AS id, ag.fecha, ag.hora_inicio, ag.hora_fin, ag.cupo,  COUNT(r.cliente_id) AS Clientes_Inscritos, s.id AS servicio_id, s.nombre AS servicio_nombre, s.descripcion AS servicio_descripcion, s.tipo AS servicio_tipo, s.imagen_servicio FROM  actividades_grupales AS ag JOIN servicios AS s ON ag.servicio_id = s.id LEFT JOIN reservas AS r ON ag.id = r.actividad_grupal_id GROUP BY ag.id;";

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
        $vSql = "SELECT ag.id, ag.servicio_id, ag.fecha, ag.hora_inicio, ag.hora_fin, ag.cupo, COUNT(r.cliente_id) AS clientes_inscritos, s.imagen_servicio FROM actividades_grupales ag LEFT JOIN reservas r ON ag.id = r.actividad_grupal_id LEFT JOIN servicios s ON ag.servicio_id = s.id WHERE ag.id = $id;";
        // Ejecutar la consulta
        $vResultado = $this->enlace->ExecuteSQL($vSql);

        if (empty($vResultado)) {
            // Si no se encontró ninguna actividad con el ID dado, retornar un arreglo vacío.
            return [];
        }

        // Obtener el resultado de la actividad
        $vResultado = $vResultado[0];

        // Consulta SQL para obtener los clientes inscritos
        $vSql2 = "SELECT CONCAT(c.nombre, ' ', c.apellidos) AS nombre_completo FROM clientes c INNER JOIN reservas r ON c.id = r.cliente_id INNER JOIN actividades_grupales ag ON r.actividad_grupal_id = ag.id WHERE ag.id = $id";
        // Ejecutar la consulta
        $vResultado2 = $this->enlace->ExecuteSQL($vSql2);

        // Asignar el resultado de los clientes inscritos
        $vResultado->Clientes_Inscritos = empty($vResultado2) ? [] : $vResultado2;

        // Retornar el objeto
        return $vResultado;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

    public function create($objeto)
    {
        try {
            //Consulta SQL para insertar el ejercicio
            $vSql = "INSERT INTO `actividades_grupales` (`servicio_id`, `fecha`, `hora_inicio`, `hora_fin`, `cupo`) VALUES ('$objeto->servicio_id', '$objeto->fecha', '$objeto->hora_inicio', '$objeto->hora_fin', '$objeto->cupo')";
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
            $vSql = "UPDATE `actividades_grupales` SET `fecha` = '$objeto->fecha', `hora_inicio` = '$objeto->hora_inicio', `hora_fin` = '$objeto->hora_fin', `cupo` = '$objeto->cupo' WHERE `actividades_grupales`.`id` = $objeto->id";
            //Ejecutar la consulta
            $vResultado = $this->enlace->executeSQL_DML($vSql);
            // Retornar el objeto actualizado
            return $this->get($objeto->id);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
