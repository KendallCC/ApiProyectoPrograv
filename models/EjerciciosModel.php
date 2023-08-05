<?php
class EjercicioModel
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
            $vSql = "SELECT * FROM ejercicios;";

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
            $vSql = "SELECT * FROM ejercicios where id=$id";

            //Ejecutar la consulta
            $vResultado = $this->enlace->ExecuteSQL($vSql);

            $vResultado = $vResultado[0];

            $vSql2 = "SELECT imagen_ejercicio FROM ejercicio_imagen WHERE id_ejercicio = $id;";
            $imagenesEjercicio = $this->enlace->ExecuteSQL($vSql2);
            $vResultado->imagenes_ejercicio = $imagenesEjercicio;


            // Retornar el objeto
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



    // public function create($objeto) {
    //     try {
    //         //Consulta sql
    //         //Identificador autoincrementable

    // 		$vSql = "INSERT INTO ejercicios (nombre, descripcion, equipamiento) VALUES ('$objeto->nombre','$objeto->descripcion','$objeto->equipamiento')";

    //         //Ejecutar la consulta
    // 		$vResultado = $this->enlace->executeSQL_DML_last( $vSql);
    // 		// Retornar el objeto creado
    //         $segundosql="INSERT INTO ejercicio_imagen (imagen_ejercicio, id_ejercicio) VALUES ( '$objeto->imagen_ejercicio' , $vResultado )";

    //         $segundoResultado = $this->enlace->executeSQL($segundosql);


    //         return $this->get($vResultado);
    // 	} catch ( Exception $e ) {
    // 		die ( $e->getMessage () );
    // 	}
    // }



    public function create($objeto)
    {
        try {
            //Consulta SQL para insertar el ejercicio
            $vSql = "INSERT INTO ejercicios (nombre, descripcion, equipamiento) VALUES ('$objeto->nombre','$objeto->descripcion','$objeto->equipamiento')";
            $vResultado = $this->enlace->executeSQL_DML_last($vSql);

            // Obtener el ID del ejercicio recién creado
            $idEjercicio = $vResultado;

            // Insertar las imágenes relacionadas al ejercicio
            foreach ($objeto->imagenes_ejercicio as $imagen) {
                $segundosql = "INSERT INTO ejercicio_imagen (imagen_ejercicio, id_ejercicio) VALUES ('$imagen', $idEjercicio)";
                $this->enlace->executeSQL($segundosql);
            }

            // Retornar el objeto creado
            return $this->get($vResultado);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



    public function update($objeto)
    {
        try {
            //Consulta sql para actualizar el ejercicio
            $vSql = "UPDATE ejercicios SET nombre = '$objeto->nombre', descripcion = '$objeto->descripcion', equipamiento = '$objeto->equipamiento' WHERE ejercicios.id = $objeto->id";
            //Ejecutar la consulta
            $vResultado = $this->enlace->executeSQL_DML($vSql);

            // Validar que el array de nuevas imágenes no esté vacío antes de continuar
            if (is_array($objeto->imagenes_ejercicio) && count($objeto->imagenes_ejercicio) > 0) {
                // Eliminar las imágenes actuales del ejercicio en la tabla ejercicio_imagen
                $deleteSql = "DELETE FROM ejercicio_imagen WHERE id_ejercicio = $objeto->id";
                $this->enlace->executeSQL($deleteSql);

                // Insertar las nuevas imágenes relacionadas al ejercicio
                foreach ($objeto->imagenes_ejercicio as $imagen) {
                    $insertSql = "INSERT INTO ejercicio_imagen (imagen_ejercicio, id_ejercicio) VALUES ('$imagen', '$objeto->id')";
                    $this->enlace->executeSQL($insertSql);
                }
            }

            // Retornar el objeto actualizado
            return $this->get($objeto->id);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
