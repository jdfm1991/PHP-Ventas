<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
$id_pedido = $_POST["id"];
$borra=false;
$borra = $modelo->DeleteSQL("saux","id_pedido = '$id_pedido'");
if ($borra){
echo "<script language=Javascript>pedido_clie();</script>";
}else{
echo "<script language=Javascript>alert('ESTE ITEM NO PUDO SER ELIMINADO');</script>";
}
echo "<script language=Javascript>prehide();</script>";
?>