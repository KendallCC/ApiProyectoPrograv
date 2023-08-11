<?php
class PlanesModel
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
			$vSql = "SELECT * FROM planes;";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ($vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    /*Obtener */
    // public function get($id)
    // {
    //     try {
    //         //Consulta sql
	// 		$vSql = "SELECT * FROM planes where id=$id";
			
    //         //Ejecutar la consulta
	// 		$vResultado = $this->enlace->ExecuteSQL ( $vSql);
	// 		// Retornar el objeto
	// 		return $vResultado;
	// 	} catch ( Exception $e ) {
	// 		die ( $e->getMessage () );
	// 	}
    // }
 


    public function get($id)
    {
        try {
            $servicios=new ServiciosModel();
            //Consulta sql
			$vSql = "SELECT * FROM planes where id=$id";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
            $vResultado=$vResultado[0];
            //listar los servicios por el plan
            $ServiciosPlan=$servicios->getByPlan($id);
            if(!empty($ServiciosPlan)){
                $ServiciosPlan = array_column($ServiciosPlan, 'nombre');
            }else{
               $ServiciosPlan=[]; 
            }
			// Retornar el objeto
            $vResultado->servicios=$ServiciosPlan;
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }


    public function create($objeto) {
        try {
           // Obtener datos del objeto JSON para la tabla planes
           $nombre = $objeto->nombre;
           $descripcion = $objeto->descripcion;
           $precio = $objeto->precio;

           // Consulta SQL para INSERT en la tabla planes
           $vSql = "INSERT INTO planes (nombre, descripcion, precio) VALUES ('$nombre', '$descripcion', $precio)";

           // Ejecutar la consulta y obtener el ID del último plan insertado
           $lastInsertedPlanID = $this->enlace->executeSQL_DML_last($vSql);

           // Verificar si hay servicios asociados al plan y realizar la inserción en la tabla planes_servicios
           if (is_array($objeto->servicios)) {
               // Consulta SQL para INSERT en la tabla planes_servicios
               $vSqlServicios = "INSERT INTO planes_servicios (plan_id, servicio_id) VALUES ";

               // Recorrer el array de servicios y construir los valores para la inserción
               $values = [];
               foreach ($objeto->servicios as $servicio_id) {
                   $values[] = "($lastInsertedPlanID, $servicio_id)";
               }

               $vSqlServicios .= implode(",", $values);

               // Ejecutar la consulta para insertar los registros en planes_servicios
               $this->enlace->ExecuteSQL($vSqlServicios);
           }
			// Retornar el objeto creado
            return $this->get($vResultado);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    public function update($objeto) {
        try {
            $id = $objeto->id;
            $nombre = $objeto->nombre;
            $descripcion = $objeto->descripcion;
            $precio = $objeto->precio;

            // Consulta SQL para UPDATE en la tabla planes
            $vSql = "UPDATE planes SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio WHERE id = $id";

            // Ejecutar la consulta para actualizar el plan
            $this->enlace->ExecuteSQL($vSql);

            // Verificar si hay servicios asociados al plan y realizar la actualización en la tabla planes_servicios
            if (is_array($objeto->servicios)) {
                // Consulta SQL para DELETE los registros antiguos de planes_servicios asociados a este plan
                $vSqlDelete = "DELETE FROM planes_servicios WHERE plan_id = $id";
                $this->enlace->ExecuteSQL($vSqlDelete);

                // Consulta SQL para INSERT en la tabla planes_servicios con los nuevos servicios asociados al plan
                $vSqlServicios = "INSERT INTO planes_servicios (plan_id, servicio_id) VALUES ";

                // Recorrer el array de servicios y construir los valores para la inserción
                $values = [];
                foreach ($objeto->servicios as $servicio_id) {
                    $values[] = "($id, $servicio_id)";
                }

                $vSqlServicios .= implode(",", $values);

                // Ejecutar la consulta para insertar los nuevos registros en planes_servicios
                $this->enlace->ExecuteSQL($vSqlServicios);
            }
            
            return $this->get($objeto->id);
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }



    public Function serviciosbyidCliente($id){
        try {
            $sql="SELECT plan_id FROM historial_planes WHERE cliente_id = $id AND estado_Plan = 'Activo' ORDER BY fecha_vigencia DESC LIMIT 1;";
       $vResultado= $this->enlace->ExecuteSQL($sql);
      $id= $vResultado[0];
      $idplan=$id-> plan_id;
        $vSql="SELECT s.id, s.nombre, s.descripcion, s.tipo, s.imagen_servicio FROM servicios s JOIN planes_servicios ps ON s.id = ps.servicio_id WHERE ps.plan_id = $idplan;";
        $vResultado= $this->enlace->ExecuteSQL($vSql);
        return $vResultado;
        } catch (Exception $e ) {
            die ( $e->getMessage () );
        }
    }



//Reserva del plan
public Function HistorialPlanes(){
    try {
        $sql="SELECT * FROM historial_planes";
   $vResultado= $this->enlace->ExecuteSQL($sql);
    return $vResultado;
    } catch (Exception $e ) {
        die ( $e->getMessage () );
    }
}

public Function HistorialPlanesbyid($id){
    try {
        $sql="SELECT * FROM historial_planes where id=$id";
   $vResultado= $this->enlace->ExecuteSQL($sql);
    return $vResultado;
    } catch (Exception $e ) {
        die ( $e->getMessage () );
    }
}

public Function HistorialPlanesbyidCliente($id){
    try {
        $sql="SELECT * FROM historial_planes WHERE cliente_id=$id and estado_Plan='Activo'";
   $vResultado= $this->enlace->ExecuteSQL($sql);
    return $vResultado;
    } catch (Exception $e ) {
        die ( $e->getMessage () );
    }
}

    public function ContratarPlan($Contratacion){
        try {
            $sql="INSERT INTO historial_planes (cliente_id, plan_id, fecha_vigencia, estado_Plan) VALUES ($Contratacion->cliente_id, $Contratacion->plan_id, '$Contratacion->fecha_vigencia', '$Contratacion->estado_Plan');";
       $vResultado= $this->enlace->executeSQL_DML_last($sql);
        return ['Contratacion realizada con exito'];
        } catch (Exception $e ) {
            die ( $e->getMessage () );
        }
        
    }
    
    public function ActualizarContratoPlan($objeto){
        try {
            $sql="UPDATE historial_planes SET estado_Plan = '$objeto->estado' WHERE plan_id = $objeto->plan_id";
       $vResultado= $this->enlace->executeSQL_DML_last($sql);
        return ['Contratacion realizada con exito'];
        } catch (Exception $e ) {
            die ( $e->getMessage () );
        }
        
    }
//termina reserva del plan
}
