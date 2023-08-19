<?php
class UserModel {
    public $enlace;

    public function __construct() {
        $this->enlace = new MySqlConnect();
    }

    public function all(){
        try {
            $vSql = "SELECT * FROM clientes;";
            $vResultado = $this->enlace->ExecuteSQL($vSql);
            return $vResultado;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get($id){
        try {
            $vSql = "SELECT * FROM clientes WHERE id=$id";
            $vResultado = $this->enlace->ExecuteSQL($vSql);

            if ($vResultado==null) {
                return null;
            }

            $vSql = "SELECT * FROM gimnasio.historial_planes where cliente_id=$id;";
            $Planes = $this->enlace->ExecuteSQL($vSql);

            $vResultado=$vResultado[0];
            $vResultado->planes=$Planes;

            return $vResultado ?? null;


            
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create($object){
        try {

            if(isset($object->contraseña)&& $object->contraseña!=null){
				$crypt=password_hash($object->contraseña, PASSWORD_BCRYPT);
				$object->contraseña=$crypt;
			}

            $identificacion = $object->identificacion;
            $nombre = $object->nombre;
            $apellidos = $object->apellidos;
            $sexo = $object->sexo;
            $fecha_nacimiento = $object->fecha_nacimiento;
            $telefono = $object->telefono;
            $fecha_inscripcion = $object->fecha_inscripcion;
            $correo_electronico = $object->correo_electronico;
            $contraseña = $object->contraseña;

            $vSql = "INSERT INTO clientes (identificacion, nombre, apellidos, sexo, fecha_nacimiento, telefono, fecha_inscripcion, correo_electronico, contraseña) VALUES ('$identificacion', '$nombre', '$apellidos', '$sexo', '$fecha_nacimiento', '$telefono', '$fecha_inscripcion','$correo_electronico', '$contraseña')";
            $this->enlace->ExecuteSQL($vSql);

            return array("message" => "Cliente insertado correctamente.");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update($object){
        try {
            $id = $object->id;
            $nombre = $object->nombre;
            $apellidos = $object->apellidos;
            $sexo = $object->sexo;
            $fecha_nacimiento = $object->fecha_nacimiento;
            $telefono = $object->telefono;
            $estado = $object->estado;

            $vSql = "UPDATE clientes SET nombre = '$nombre', apellidos = '$apellidos', sexo = '$sexo', fecha_nacimiento = '$fecha_nacimiento', telefono = '$telefono', estado = '$estado' WHERE id = $id";
            $this->enlace->ExecuteSQL($vSql);

            return array("message" => "Cliente actualizado correctamente.");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function updateRol($object){
        try {
            
            $rol = $object->role;
            $id = $object->id;

            $vSql = "UPDATE clientes SET rol = '$rol' WHERE id = $id";
            $this->enlace->ExecuteSQL($vSql);

            return array("message" => "Cliente actualizado correctamente.");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function login($objeto) {
        try {
            
			$vSql = "SELECT * from clientes where correo_electronico='$objeto->email'";
			
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
			if(is_object($vResultado[0])){
				$user=$vResultado[0];
				if(password_verify($objeto->password, $user->contraseña))  
                    {
						return $this->get($user->id);
					}

			}else{
				return false;
			}
           
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
}
?>
