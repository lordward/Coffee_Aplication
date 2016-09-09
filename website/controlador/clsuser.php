<?php 
    require_once("modelo.php");
	class Usuario extends Database{
		static $idusuario;	function setidUsuario($idusuario){$this->idusuario = $idusuario;}	function getidUsuario(){return($this->idusuario);}
		static $nombre;		function setNombre($nombre){$this->nombre = $nombre;}				function getNombre(){return($this->nombre);	}
		static $pass;		function setPass($pass){$this->pass = $pass;}						function getPass(){return($this->pass);}
		static $tipo;		function setTipo($tipo){$this->tipo = $tipo;}					    function getTipo(){return($this->tipo);}
		static $foto;		function setFoto($foto){$this->foto = $foto;}					    function getFoto(){return($this->foto);}
		static $activo;		function setActivo($activo){$this->activo = $activo;}			    function getActivo(){return($this->activo);}

		//-------------------------------------------------------
		// Z O N A   P R I V A D A
		//-------------------------------------------------------
		function leerFicha($password){  /*Lee la ficha de un usuario*/
			$this->conectar();
			$sql = "SELECT * FROM cafe_usuarios WHERE password = '".$password."' AND ACTIVO=1;";
	        $volver = false;
	        $result=$this->consulta($sql);
			if ($result->num_rows>0) {
				$obj = $result->fetch_object();
				$this->setidUsuario($obj->Id);
				$this->setNombre($obj->nombre);
				$this->setPass($obj->password);
				$this->setTipo($obj->tipo);
				$this->setFoto($obj->foto);
        		$volver = true;
			}	
	        $this->desconectar();
	        return $volver;
		}

		function contarCafes($idUsuario){ /*Devuelve la suma de cafes que ha tomado un usuario*/
			$this->conectar();
			$sql = "SELECT sum(`cafe_consumo`.`cantidad`) AS `cafes` FROM `cafe_consumo` WHERE `cafe_consumo`.`idusuario` = ".$idUsuario.";";
			$cafes=0;
	        $result=$this->consulta($sql);
			if ($result->num_rows>0) {
				$obj = $result->fetch_object();
				$cafes=($obj->cafes);
			}	
	        $this->desconectar();
	        if ($cafes=="") $cafes=0;
	        return $cafes;
		}

		function contarCafesPagados($idUsuario){ /*Devuelve la suma de cafes pagados por 1 usuario*/
			$this->conectar();
			$sql= "SELECT Sum(`cafe_pagos`.`cafes`) AS `cafes` FROM  `cafe_pagos` WHERE `cafe_pagos`.`idusuario` = ".$idUsuario.";";
			$cafes=0;
	        $result=$this->consulta($sql);
			if ($result->num_rows>0) {
				$obj = $result->fetch_object();
				$cafes=($obj->cafes);
			}	
	        $this->desconectar();
	        if ($cafes=="") $cafes=0;
	        return $cafes;
		}

		function leerCafes($idusuario){ /*Numero de cafes que ha tomado un usuario*/
			$this->conectar();
			$sql= "SELECT Id, fecha, cantidad FROM cafe_consumo WHERE idusuario = ".$idusuario." ORDER BY fecha DESC LIMIT 15;";		
			$result=$this->consulta($sql);
	        $this->desconectar();
	        return $result;
		}

		function leerCafesPagados($idusuario){  /*Numero de cafes que ha pagado un usuario*/
			$this->conectar();
			$sql= "SELECT fecha, cafes FROM cafe_pagos WHERE idusuario = ".$idusuario." ORDER BY fecha DESC;";		
			$result=$this->consulta($sql);
	        $this->desconectar();
	        return $result;
		}

		function leerEstadisticas($idusuario){ /*Numero de cafes que ha tomado un usuario*/
			$this->conectar();
			if ($idusuario > 0) {
				$sql= "SELECT Left(fecha, 7) AS 'fecha', Sum(cantidad) AS 'sumacantidad' FROM cafe_consumo WHERE idusuario = ".$idusuario." GROUP BY Left(fecha, 7) ORDER BY fecha;";		
			}else{
				$sql ="SELECT Left(fecha, 7) AS 'fecha', Sum(cantidad) AS 'sumacantidad', Count(DISTINCT idusuario) AS 'numusuarios' FROM cafe_consumo GROUP BY Left(fecha, 7) ORDER BY fecha;";
			}
			$result=$this->consulta($sql);
	        $this->desconectar();
	        return $result;
		}

        function nuevoCafe($idusuario, $fecha, $cantidad){   /*Apuntar el consumo de 1/varios cafes que toma 1 usuario*/
			$this->conectar();
			//$fecha = substr($fecha, -4). "-". substr($fecha, 3, 2) ."-". substr($fecha, 0, 2);
			$sql= "INSERT INTO cafe_consumo (idusuario, fecha, cantidad) VALUES (".$idusuario.",'".$fecha."',".$cantidad.");";
			$this->consulta($sql);
	        $this->desconectar();
		}		

		function listaUsuarios(){ /*Listado de usuario activos*/
			$this->conectar();
			$sql= "SELECT Id, nombre FROM cafe_usuarios WHERE tipo <> 'ADMIN' AND activo = 1 ORDER BY nombre;";
			$result=$this->consulta($sql);
	        $this->desconectar();
	        return $result;
		}

		function listaPagoCafes(){ /*Listado de usuarios y fecha de cafes que han pagado*/
			$this->conectar();
			$sql= "SELECT nombre, fecha, cafes FROM cafe_usuarios INNER JOIN cafe_pagos ON cafe_pagos.idusuario = cafe_usuarios.Id WHERE
  			tipo <> 'ADMIN' AND activo = 1 ORDER BY nombre, fecha DESC;";
			$result=$this->consulta($sql);
	        $this->desconectar();
	        return $result;
		}

		function nuevoPagoCafe($idusuario, $fecha, $cafes){  /*Añade un registro cuando un usuario paga cafes a tabla pagos*/
			$this->conectar();
			//$fecha = substr($fecha, -4). "-". substr($fecha, 3, 2) ."-". substr($fecha, 0, 2);
			$sql= "INSERT INTO cafe_pagos (idusuario, fecha, cafes) VALUES (".$idusuario.",'".$fecha."',".$cafes.");";
			$this->consulta($sql);
	        $this->desconectar();
		}

	}
?>