<?php 
session_start();
require("conexion.php");
require("modelo.php");
$user = $_POST['usuario'];
$pass = md5($_POST['clave']);
$modelo = new Modelo();
$datos=$modelo->consultaSQL("select Login AS CodVend from APPWEB_CONFITERIAGUAYANA.[dbo].Usuarios where Login = '$user' and Clave = '$pass'"); 
$clientes='';
	foreach ($datos as $row) {
		$clientes = $row["CodVend"];
	}
	if ($clientes != ''){
		$datos=$modelo->consultaSQL("select Clase from savend where codvend = '$user'"); 
		$canales='';
			foreach ($datos as $row) {
				$canales = $row["Clase"];
			}
		$_SESSION['canal'] = $canales;
		$_SESSION['open'] = $user;
		echo "<script language=Javascript> location.href=\"index.php\";</script>";
	}else{
		echo "Usuario No Registrado";
	}
?>