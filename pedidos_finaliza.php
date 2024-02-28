<?php 
session_start();
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> alert('SU SESSION DE USUARIO HA FINALIZADO, DEBE INGRESAR NUEVAMENTE');</script>";
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
$codvend = $_SESSION['open'];
$clie_prod = $_POST['codclie'];
$nota = $_POST['nota'];
$contadorProd=0;
$ver_productos = $modelo->consultaSQL("select * from saux where codclie = '$clie_prod' and estatus = '0' and codvend = '$codvend' order by id_pedido desc");
foreach ($ver_productos as $row) {
	$contadorProd++;
}
$n_ver_productos = ($contadorProd);

$iva = 0;
$pedidos = 0;
$precio_sin_iva = 0;
$total = 0;
$exen = 0;

$query_iva = $modelo->consultaSQL("select MtoTax from sataxes where codtaxs = 'IVA'");

foreach ($query_iva as $row4) {
	$MtoTax = $row4["MtoTax"];
}

if ($n_ver_productos != 0){//A
	foreach ($ver_productos as $row) {//B
	$precio_sin_iva = $precio_sin_iva + ($row["precio"]  * $row["pedido"]);
	$preciosd = $row["precio"];
	$excentod = $row["esexcento"];
	$pp_pedido = $row["pedido"];
	if ($excentod == 0){
	$iva = $iva + (($preciosd * $pp_pedido) * ($MtoTax/100)); 
	}else{
	$exen = $exen + ($preciosd * $pp_pedidos);
	}
	}//B
$total = $iva + $precio_sin_iva;

///SACORRELIS
$safact = $modelo->consultaSQL("select TOP 1 * from SAFACT where TipoFac = 'F' and CodSucu='00000' ORDER BY NroUnico DESC");
	$contador2=0;	
	foreach ($safact as $row33) {
		$contador2++;
	}
$correlativos = $modelo->consultaSQL("select * from SACORRELSIS where fieldname = 'LenCorrel' and CodSucu='00000'");
$longitud=0;
	foreach ($correlativos as $row44) {
		$longitud= $row44["ValueInt"];
	}
$sacorre = $modelo->consultaSQL("select * from SACORRELSIS where fieldname = 'PrxProf' and CodSucu='00000'");
	foreach ($sacorre as $row44) {
		$n_value = $row44["ValueInt"];
	}
	
if ($n_value > 1){
$numerod_fin = str_pad($n_value, $longitud, "0", STR_PAD_LEFT);
$n_value = $n_value + 1;

$edita_sacorre = $modelo->UpdateSQL("SACORRELSIS", "ValueInt = '$n_value'", "fieldname = 'PrxProf'");


}
if ($n_value == 1 and ($contador2)== 0){
$numerod_fin = str_pad($n_value, $longitud, "0", STR_PAD_LEFT);
$n_value = $n_value + 1;
$edita_sacorre = $modelo->UpdateSQL("SACORRELSIS", "ValueInt = '$n_value'", "fieldname = 'PrxProf'"); 
}



$safact_2 = $modelo->consultaSQL("select TOP 1 * from SAFACT where CodSucu='00000' ORDER BY NroUnico DESC");
	$contadorSafact_2 = 0;
	$NroUnico =0;
	foreach ($safact_2 as $row33) {
		$contadorSafact_2++;
		$NroUnico = $row33["NroUnico"];
	}
if ($contadorSafact_2!= 0){ //SAFACT 2
$nrounico = (int)$NroUnico + 1;		
}else{ //SAFACT 2
$nrounico = 1;
}  //SAFACT 2

$cod_ubicates = "01";
if($_SESSION['open'] == 'PZOD01F'){
$cod_ubicates = "99"; 
}
if($_SESSION['open'] == 'PZOD02F'){
$cod_ubicates = "99"; 
}
if($_SESSION['open'] == 'PZOD03F'){
$cod_ubicates = "99"; 
}
if($_SESSION['open'] == 'PZOU01F'){
$cod_ubicates = "99"; 
}
if($_SESSION['open'] == 'PZOU02F'){
$cod_ubicates = "99"; 
}


$cod_estacion = "MOBILE";
$cod_usuario = "APP";

	$info_clie = $modelo->consultaSQL("select * from SACLIE where codclie = '$clie_prod'");

	foreach ($info_clie as $row44) {

		$info_descrip = $row44["Descrip"];
		$info_direct1 = $row44["Direc1"];
		$info_direct2 = $row44["Direc2"];
		$info_zona = $row44["CodZona"];
		$info_pvp = $row44["TipoPVP"];
		$info_pais = $row44["Pais"];
		$info_ciudad = $row44["Ciudad"];
		$info_estado = $row44["Estado"];
		$info_id3 = $row44["ID3"];
		$info_tele = $row44["Telef"];
		$info_saldo = $row44["Saldo"];
	}


$info_codvend = $_SESSION['open'];

$factor = 1;
$credito= $precio_sin_iva + $iva;

	$insertar = $modelo->InsertSQL("SAFACT", "NroUnico,TipoFac, NumeroD, CodSucu, CodEsta, CodUsua, Signo, Factor, CodClie, CodVend,CodUbic,Descrip,Direc1,Direc2,Telef,ID3,Monto,MtoTax,TGravable,TExento,FechaE,FechaV,TotalPrd,SaldoAct,FechaI,MtoTotal,Credito,FechaT,Notas1", "$nrounico,'F','$numerod_fin','00000','SERVER88','WEB','$factor','$factor','$clie_prod','$info_codvend','$cod_ubicates','$info_descrip','$info_direct1','$info_direct2','$info_tele','$info_id3','$precio_sin_iva','$iva','$precio_sin_iva','$exen',getdate(),getdate(),'$precio_sin_iva','$info_saldo',getdate(),'$total','$credito',getdate(),'$nota'");

//SAITEMFACT
$campos	='TipoFac,NumeroD,NroLinea,CodItem,CodUbic,CodSucu,CodVend,CantMayor,Descrip1,Descrip2,Refere,Signo,Cantidad,ExistAnt,TotalItem,Costo,Precio,PriceO,FechaE,EsUnid,EsExento,MtoTax';
	$cont = $n_ver_productos;
	foreach ($ver_productos as $row55){  //SAITEMFACT 1	
	$cod_prod = $row55["codprod"];
	$pedidoss = $row55["pedido"];
	$precios = $row55["precio"];
	$unidad = $row55["unidad"];
	$idsaux = $row55["id_pedido"];
	$items = $pedidoss * $precios;
	$tax_exen=0;
	
	$verprod = $modelo->consultaSQL("SELECT Descrip,Descrip2,EsExento,CostPro,Existen,Compro FROM SAPROD WHERE CodProd = '$cod_prod'");

		$descrip1 = $descrip2 =  '';
		$existen = $costoprod = $esexcest = 0;

		foreach ($verprod as $row56){
			$descrip1 =$row56["Descrip"];
			$descrip1 = str_replace("'", "''", $descrip1);
			$descrip2 =$row56["Descrip2"];
			$existen =$row56["Existen"];
			$costoprod =$row56["CostPro"];
			$esexcest =$row56["EsExento"];
		}
	if ($esexcest==0) {
		$tax_exen = $items*($MtoTax/100);
		}
		$saitemfact = $modelo->InsertSQL("SAITEMFAC", "$campos", "'F','$numerod_fin','$cont','$cod_prod','$cod_ubicates','00000','$info_codvend',1,'$descrip1','01','$cod_prod',1,'$pedidoss','0',$items,'$costoprod','$precios','$precios',getdate(),'$unidad','$esexcest','$tax_exen'");
		$cont--;
		$edita_numerod = $modelo->UpdateSQL("saux", "numerod = '$numerod_fin'", "id_pedido = '$idsaux'");	
} //SAITEMFACT 1

	if ($insertar and $saitemfact){ ///SACORRELIS 2
		$edita_saux = $modelo->UpdateSQL("saux", "estatus = 1", "codclie = '$clie_prod'");
	echo "PEDIDO AGREGADO. NRO DE REFERENCIA DEL PEDIDO: $numerod_fin";
	} ///SACORRELIS 2			

}else{//A
	if ($n_ver_productos == 0){
echo "<script language=Javascript> alert('ERROR, DEBE EXISTIR AL MENOS UN PEDIDO'); </script>";
    }

echo "<script language=Javascript> reinicio(); </script>";
}//A
echo "<script language=Javascript>prehide();</script>";
?>