<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php 
echo "hola";
/*require("conexion.php");*/
?>
<body>
<?php
/*function rdecimal($valor) {
   $float_redondeado=round($valor * 100) / 100;
   return $float_redondeado;
} */
 /*?>$numerod = $_GET['numd'];
$tipo = $_GET['tipo'];
if ($tipo == "A"){
$tipo = 'FACT ';
}
if ($tipo == "10"){
$tipo = 'FACT ';
}
if ($tipo == "B"){
$tipo = 'DEV ';
}
if ($tipo == "20"){
$tipo = 'N/D ';
}
$consult_fact = mssql_query("select numerod, safact.codvend as vendedor, safact.codclie as codcliente, safact.descrip as cliente, safact.fechae as fechaemi, mtototal, monto, descto1, mtotax  from safact inner join saclie on  safact.codclie = saclie.codclie where numerod = '0000069121'");
$consult_fact_items = mssql_query("select * from saitemfac where numerod = '0000069121' order by nrolinea");
$num = mssql_num_rows($consult_fact_items);
?>
<div data-role="header" data-theme="b">
    <h1><?php echo $tipo." ".$numerod; ?></h1>
</div>	

<p><strong>Cliente: <?php echo mssql_result($consult_fact,0,"cliente");  ?>, Ruta: <?php echo mssql_result($consult_fact,0,"vendedor");  ?></strong></p>
<p><strong>Fecha Emisión: <?php echo mssql_result($consult_fact,0,"fechaemi");  ?></strong></p><?php */?>
<!--<table data-role="table" id="table-column-toggle"  class="ui-responsive table-stroke">
	<tr class="ui-btn-active">
	<td><div align="center"><strong>Codprod</strong></div></td>
    <td><div align="center"><strong>Descrip</strong></div></td>
    <td><div align="center"><strong>Cant</strong></div></td>
	<td><div align="center"><strong>Unidad</strong></div></td>
	<td><div align="center"><strong>Monto</strong></div></td> 
	</tr>  
	</table>-->
	
 <?php /*?> <?php
  for($i=0;$i<$num;$i++){
  ?>
  <tr>
  	<td><div align="center"><?php echo mssql_result($consult_fact_items,$i,"coditem");  ?></div></td>
    <td><div align="center"><?php echo utf8_encode(mssql_result($consult_fact_items,$i,"descrip1")); ?></div></td>
    <td><div align="center"><?php echo mssql_result($consult_fact_items,$i,"cantidad"); ?></div></td>
	<td><div align="center">
	<?php if (mssql_result($consult_fact_items,$i,"esunid") == 1){
	echo "Paq";
	}else{
	echo "Bul";
	} ?></div></td>
	<td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact_items,$i,"totalitem")),2, ",", "."); ?></div></td>
  </tr>
  <?php } ?>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Sub Total </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"monto")),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Descuento </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"descto1")),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Base Imponible </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"monto")),2, ",", "."); ?></div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Impuestos 12% </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"mtotax")),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Monto Total </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"mtototal")),2, ",", "."); ?> </div></td>
  </tr>
</table><?php */?>
</body>
</html>