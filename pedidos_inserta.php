<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
session_start();
set_time_limit(0);
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> alert('SU SESSION DE USUARIO HA FINALIZADO, DEBE INGRESAR NUEVAMENTE');</script>";
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
$codvend = $_SESSION['open'];
$codclie = $_POST['codclie'];
$num = $_POST['num'];
$aux = 0;

$datos_clientes = $modelo->consultaSQL("select descrip, tipopvp from saclie where codclie = '$codclie' order by descrip");
$cliente = $pvp ='';
foreach ($datos_clientes as $row) {
	$cliente = utf8_decode($row["descrip"]);
	$pvp = $row["tipopvp"];
}

for($i=0;$i<$num;$i++){
$codprod = $_POST['codprod'.$i];
$unidad = $_POST['unidad'.$i];
$cant = $_POST['cant'.$i];
	if ($cant > 0){
		$datos_productos = $modelo->consultaSQL("select descrip, precio1, precio2, precio3, esexento, preciou as preciou1, preciou2, preciou3 from saprod where codprod = '$codprod'");
		foreach ($datos_productos as $row) {
			$excento = ($row["esexento"]);
			$producto = utf8_decode($row["descrip"]);
			$producto = str_replace("'", "''", $producto);
			if ($unidad == 0) {
				$precio = $row["precio". $pvp];
			} else {
				$precio = $row["preciou" . $pvp];
			}
		}

		$datos_lineas = $modelo->consultaSQL("select linea from SAUX where codclie = '$codclie' and estatus = 0 order by linea desc");
		$contadorLinea=0;
		foreach ($datos_lineas as $row) {
			$contadorLinea++;
			$linea = $row["linea"]; 
		}

		if ($contadorLinea!=0){
			$linea = $linea + 1;
		}else{
			$linea = 1;
		}
		$estatus = 0;
		if ($linea <= 19) {
			$insertar = $modelo->InsertSQL("saux", "codvend,codclie,cliente,precioclie,codprod,descriprod,unidad,precio, esexcento,pedido,linea,fecha,estatus", "'$codvend','$codclie','$cliente','$pvp','$codprod','$producto','$unidad','$precio','$excento','$cant','$linea',getdate(),'$estatus'");
		}else{
			break;
		}
	}
}
echo "<script language=Javascript>prehide();</script>";
echo "<script language=Javascript> reinicio(); </script>";


?>
