<?php
class EjerciciosImagenesModel
{
    public $enlace;
    public function __construct()
    {
        $this->enlace = new MySqlConnect();
    }
    /*Listar */
    public function all(){
        try {
            //Consulta sql
			$vSql = "SELECT * from ejercicio_imagen;";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ($vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    /*Obtener */
    public function get($id)
    {
        try {
            //Consulta sql
			$vSql = "SELECT ei.imagen_ejercicio, e.nombre
            FROM gimnasio.rutinas_ejercicios AS re
            JOIN gimnasio.ejercicio_imagen AS ei ON re.ejercicio_id = ei.id_ejercicio
            JOIN gimnasio.ejercicios AS e ON re.ejercicio_id = e.id
            WHERE re.rutina_id = $id;";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
 
    
}
