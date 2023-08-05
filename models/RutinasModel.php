<?php
class RutinasModel
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
            $vSql = "SELECT * FROM rutinas;";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            $vResultado = $vResultado;
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
            $Rutina = new RutinasEjerciciosModel();
            $imagenes = new EjerciciosImagenesModel();
            #

            //Consulta sql
            $vSql = "SELECT * FROM rutinas where id=$id;";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            $vResultado = $vResultado[0];
            $ejerciciosRutina = $Rutina->get($id);

            $ejerciciosImg = $imagenes->get($id);

            $vResultado->ejercicios = $ejerciciosRutina;
            $vResultado->imagenes = $ejerciciosImg;
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
            $vSql = "INSERT INTO rutinas (nombre, tipo) VALUES ('$objeto->nombre', '$objeto->tipo')";
            $vResultado = $this->enlace->executeSQL_DML_last($vSql);

            // Obtener el ID de la rutina recién creada
            $idRutina = $vResultado;

            // Insertar los ejercicios relacionados a la rutina en la tabla rutinas_ejercicios
            foreach ($objeto->ejercicios as $Ejercicio) {
                $sql2 = "INSERT INTO rutinas_ejercicios (rutina_id, ejercicio_id,repeticiones) VALUES ($idRutina, $Ejercicio->ejercicio_id,$Ejercicio->repeticiones)";
                $this->enlace->executeSQL($sql2);
            }

            // Retornar el objeto creado
            return $this->get($idRutina);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update($objeto)
    {
        try {
            // Consulta sql para actualizar la rutina
            $vSql = "UPDATE rutinas SET nombre = '$objeto->nombre', tipo = '$objeto->tipo' WHERE rutinas.id = $objeto->id";
            // Ejecutar la consulta
            $vResultado = $this->enlace->executeSQL_DML($vSql);

            // Validar que el array de ejercicios no esté vacío antes de continuar
            if (is_array($objeto->ejercicios) && count($objeto->ejercicios) > 0) {
                // Eliminar los ejercicios actuales de la rutina en la tabla rutinas_ejercicios
                $deleteSql = "DELETE FROM rutinas_ejercicios WHERE rutina_id = $objeto->id";
                $this->enlace->executeSQL($deleteSql);


                foreach ($objeto->ejercicios as $Ejercicio) {
                    $sql2 = "INSERT INTO rutinas_ejercicios (rutina_id, ejercicio_id,repeticiones) VALUES ($objeto->id, $Ejercicio->ejercicio_id,$Ejercicio->repeticiones)";
                    $this->enlace->executeSQL($sql2);
                }

                // Insertar los nuevos ejercicios relacionados a la rutina en la tabla rutinas_ejercicios
                // foreach ($objeto->ejercicios as $ejercicio_id) {
                //     $insertSql = "INSERT INTO rutinas_ejercicios (rutina_id, ejercicio_id,repeticiones) VALUES ('$objeto->id', '$ejercicio_id-> ejercicio_id','$ejercicio_id->repeticiones')";
                //     $this->enlace->executeSQL($insertSql);
                // }
            }

            // Retornar el objeto actualizado
            return $this->get($objeto->id);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
