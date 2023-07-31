<?php
class RutinasEjerciciosModel
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
			$vSql = "SELECT * FROM rutinas_ejercicios;";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ($vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
   
 


    public function get($id)
    {
        try {
            $servicios=new ServiciosModel();
            //Consulta sql
			$vSql = "SELECT e.*,re.repeticiones FROM rutinas_ejercicios re JOIN ejercicios e ON re.ejercicio_id = e.id WHERE re.rutina_id = $id;";
			
            // //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
           

            
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    
}
