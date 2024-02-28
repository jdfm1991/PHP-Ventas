<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
function rdecimal($valor) {
   $float_redondeado=round($valor * 100) / 100;
   return $float_redondeado;
}

$numerod = $_GET['numd'];
$tipo = $_GET['tipo'];
$tipofac = $_GET['tipo'];
if ($tipo == "A"){
$tipo = 'FACT ';
}
if ($tipo == "10"){
$tipo = 'FACT ';
$tipofac = 'A';
}
if ($tipo == "B"){
$tipo = 'DEV ';
}
if ($tipo == "20"){
$tipo = 'N/D ';
$tipofac = 'B';
}
if ($tipo == "F"){
$tipo = 'PEDIDO ';
$tipofac = 'F';
}

$consult_fact = $modelo->consultaSQL("select numerod, safact.codvend as vendedor, safact.codclie as codcliente, safact.descrip as cliente, safact.fechae as fechaemi, mtototal, monto, descto1, mtotax  from safact inner join saclie on  safact.codclie = saclie.codclie where numerod = '$numerod' and tipofac = '$tipofac' and safact.CodSucu='00000'");
$mtototal=$mtotax=$descto1 =$monto = 0;
$codcliente = $cliente = '';
foreach ($consult_fact as $row) {
  $codcliente = $row["codcliente"];
  $cliente = $row["cliente"];
  $monto = $row["monto"];
  $descto1 = $row["descto1"];
  $mtotax = $row["mtotax"];
  $mtototal = $row["mtototal"];
}

$consult_fact_items = $modelo->consultaSQL("select * from saitemfac where numerod = '$numerod' and tipofac = '$tipofac' and CodSucu='00000' order by nrolinea");
$contador = 0;
foreach ($consult_fact_items as $row) {
  $contador++;
}

$num = ($contador);
?>
<body>
<div data-role="header" data-theme="b">
    <h1><?php echo $tipo." ".$numerod; ?></h1>
</div>
<?php if ($num != 0){ ?>
<div align="center"><strong><?php echo utf8_encode($codcliente)." ".utf8_encode($cliente); ?></strong></div>
<table>
	<tr class="ui-btn-active">
	<td><div align="center"><strong>Codprod</strong></div></td>
    <td><div align="center"><strong>Descrip</strong></div></td>
    <td><div align="center"><strong>Cant</strong></div></td>
	<td><div align="center"><strong>Unidad</strong></div></td>
	<td><div align="center"><strong>Monto</strong></div></td> 
	</tr>  
<?php
  foreach ($consult_fact_items as $row) {
  ?>
  <tr>
  	<td><div align="center"><?php echo $row["CodItem"];  ?></div></td>
    <td><div align="center"><?php echo utf8_decode($row["Descrip1"] ); ?></div></td>
    <td><div align="center"><?php echo round($row["Cantidad"]); ?></div></td>
	<td><div align="center">
	<?php if ($row["EsUnid"] == 1){
	echo "Uni";
	}else{
	echo "Paq";
	} ?></div></td>
	<td><div align="center"><?php echo number_format(rdecimal($row["TotalItem"]),2, ",", "."); ?></div></td>
  </tr>
  <?php } ?>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Sub Total </div></td>
  <td><div align="center"><?php echo number_format(rdecimal($monto),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Descuento </div></td>
  <td><div align="center"><?php echo number_format(rdecimal($descto1),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Base Imponible </div></td>
  <td><div align="center"><?php echo number_format(rdecimal($monto- $descto1),2, ",", "."); ?></div></td>
  </tr>
  <tr class="ui-btn-active">
<?php

  $query_por_iva = $modelo->consultaSQL("select MtoTax from SATAXVTA where numerod = '$numerod'");
  $iva  = 0;
  foreach ($query_por_iva as $row1) {
    $iva = $row1["MtoTax"];
  }
?>
  <td colspan="4"><div align="right">Impuestos 16% </div></td>
<?php 

?>  
   <td><div align="center"><?php echo number_format($mtotax,2, ",", "."); ?> </div></td> 
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Monto Total </div></td>
  <td><div align="center"><?php echo number_format(rdecimal($mtototal),2, ",", "."); ?> </div></td>
  </tr>
</table>
</br>
<div align="center"><a href="detalle_excel.php?&numd=<?php echo $_GET['numd']; ?>&tipo=<?php echo $_GET['tipo']; ?>" target="_blank" > Imprimir en Excel</a>
  <?php 
}else{
echo "SIN DETALLES";
} 
?>	
</div>
</body>
</html>
