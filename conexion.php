<?php 
/*
$conectar = mssql_connect('localhost','sa','Intecloud..')or die("Problemas al conectar a SQL Server");
	mssql_select_db("confiteriaguayana",$conectar)or die("Problemas al seleccionar la Base de Datos");*/

	date_default_timezone_set('America/Caracas');
	include_once "const.php";

	class Conectar {
		protected $dbh;
		protected function conexion() {
			try {
				$conectar = $this->dbh = new PDO("sqlsrv:Server=".SERVER_BD_1.";Database=".NAME_BD_1,USER_BD_1,PASSWORD_BD_1);
				return $conectar;
			} catch (Exception $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
	
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		
	}
	

 ?>