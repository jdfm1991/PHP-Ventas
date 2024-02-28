<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Documento_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
function rdecimal($valor) {
   $float_redondeado=round($valor * 100) / 100;
   return $float_redondeado;
}

$numerod = $_GET['numd'];
$tipo = $_GET['tipo'];
$tipofac = $_GET['tipo'];
$codvend = $_GET['codvend'];

if ($tipo == "A"){
$tipo = 'FACT ';
}

if ($tipo == "C"){
  $tipo = 'NE ';
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
$consult_fact = mssql_query("select numerod, safact.codvend as vendedor, safact.codclie as codcliente, safact.descrip as cliente, safact.fechae as fechaemi, mtototal, monto, descto1, mtotax  from safact inner join saclie on  safact.codclie = saclie.codclie where numerod = '$numerod' and tipofac = '$tipofac' and safact.codvend='$codvend'");
$consult_fact_items = mssql_query("select * from saitemfac where numerod = '$numerod' and tipofac = '$tipofac' and OTipo not in ('E')  order by nrolinea");
$num = mssql_num_rows($consult_fact_items);

$fechaFacturado =date("d/m/Y h:i:s A",strtotime(mssql_result($consult_fact,0,"fechaemi")));

$Onumerod = mssql_result($consult_fact_items,0,"Onumero");
$saux_pedido = mssql_query("select TipoFac, OTipo, numerod, ONumero, coditem, descrip1 as descriprod, cantidad as pedido, precio, esunid as unidad, esexento as esexcento, Onumero, MtoTax, TotalItem from saitemfac where (numerod = '$Onumerod' or ONumero='$Onumerod' ) and (tipofac = 'A' or tipofac = 'c' or  tipofac = 'F')  and OTipo not in ('E') ");

$saux_pedidoFecha = mssql_query("SELECT distinct fecha FROM SAUX where numerod = '$Onumerod' order by fecha desc");

$fechaPedido = date('d/m/Y h:i:s A', strtotime(mssql_result($saux_pedidoFecha,0,"fecha")));

$num_pedido = mssql_num_rows($saux_pedido);
?>
<body>
<div data-role="header" data-theme="b">
    <h1><?php echo $tipo." ".$numerod; ?></h1>
    <h1><?php echo "PEDIDO ".$Onumerod; ?></h1>
    <h1><?php echo "RUTA ".$codvend ; ?></h1>
</div>
<?php if ($num != 0){ ?>
<h3><?php echo utf8_encode(mssql_result($consult_fact,0,"codcliente"))." ".utf8_encode(mssql_result($consult_fact,0,"cliente")); ?></h3>

<?php ///////////////////////////// PEDIDO /////////////////////////////?>
<table>
PEDIDO&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fechaPedido; ?>
	<tr bgcolor="#0099FF">
	<td><div align="center"><strong>Codprod</strong></div></td>
    <td><div align="center"><strong>Descrip</strong></div></td>
    <td><div align="center"><strong>Cant</strong></div></td>
	<td><div align="center"><strong>Unidad</strong></div></td>
	<td><div align="center"><strong>Monto</strong></div></td> 
	</tr>  
<?php
  for($i=0;$i<$num_pedido;$i++){
  ?>
  <tr  <?php if (($i % 2) != 0){ ?>
   				bgcolor="#CCCCCC"
  				<?php } ?>>
  	<td><div align="center"><?php echo mssql_result($saux_pedido,$i,"coditem");  ?></div></td>
    <td><div align="center"><?php echo utf8_decode(mssql_result($saux_pedido,$i,"descriprod")); ?></div></td>
    <td><div align="center"><?php echo round(mssql_result($saux_pedido,$i,"pedido")); ?></div></td>
	<td><div align="center">
	<?php if (mssql_result($saux_pedido,$i,"unidad") == 1){
	echo "Uni";
	}else{
	echo "Paq";
	} ?></div></td>
  <?php 
				$base = $base + mssql_result($saux_pedido,$i,"pedido")*mssql_result($saux_pedido,$i,"precio");
				if (mssql_result($saux_pedido,$i,"esexcento")==0){
				/*	if((date('Y-m-d', strtotime(normalize_date($fechai))) >= date('Y-m-d', mktime(0,0,0,10,1,2017))) and (date('Y-m-d', strtotime(normalize_date($fechai))) <= date('Y-m-d', mktime(0,0,0,12,31,2017)))){
						$por_iva = mssql_result($query_por_iva,0,"MtoTax")/100;
						$iva = $iva + ((mssql_result($saux_pedido,$i,"MtoTax")));
						$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")+mssql_result($saux_pedido,$i,"MtoTax")));
					}else{*/
						$iva = $iva + ((mssql_result($saux_pedido,$i,"MtoTax")));
						$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")+mssql_result($saux_pedido,$i,"MtoTax")));
					//}
				}else{
					$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")));
				} 
			?>
	<td><div align="center"><?php echo number_format(rdecimal(mssql_result($saux_pedido,$i,"TotalItem")),2, ",", "."); ?></div></td>
  </tr>
  <?php }
  $iva = $base * 0.16;

  $total = $base + $iva;
  ?>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Sub Total </div></td>
  <td><div align="center"><?php echo number_format($base,2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Descuento </div></td>
  <td><div align="center"><?php echo number_format(0,2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Base Imponible </div></td>
  <td><div align="center"><?php echo number_format($base,2, ",", "."); ?></div></td>
  </tr>
  <tr class="ui-btn-active">
<?php 
    $query_por_iva = mssql_query("select MtoTax from SATAXVTA where numerod = '$numerod'");
?>
  <td colspan="4"><div align="right">Impuestos 16% </div></td>

  <td><div align="center"><?php echo number_format($iva,2, ",", ".");   ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Monto Total </div></td>
  <td><div align="center"><?php echo number_format($total,2, ",", "."); ?> </div></td>
  </tr>
</table>

<br><br><br>
<?php ///////////////////////////// DESPACHADO /////////////////////////////?>

<table>
FACTURADO&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fechaFacturado; ?>
	<tr bgcolor="#0099FF">
	<td><div align="center"><strong>Codprod</strong></div></td>
    <td><div align="center"><strong>Descrip</strong></div></td>
    <td><div align="center"><strong>Cant</strong></div></td>
	<td><div align="center"><strong>Unidad</strong></div></td>
	<td><div align="center"><strong>Monto</strong></div></td> 
	</tr>  
<?php
  for($i=0;$i<$num;$i++){
  ?>
  <tr  <?php if (($i % 2) != 0){ ?>
   				bgcolor="#CCCCCC"
  				<?php } ?>>
  	<td><div align="center"><?php echo mssql_result($consult_fact_items,$i,"coditem");  ?></div></td>
    <td><div align="center"><?php echo utf8_decode(mssql_result($consult_fact_items,$i,"descrip1")); ?></div></td>
    <td><div align="center"><?php echo round(mssql_result($consult_fact_items,$i,"cantidad")); ?></div></td>
	<td><div align="center">
	<?php if (mssql_result($consult_fact_items,$i,"esunid") == 1){
	echo "Uni";
  }else{
  echo "Paq";
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
<?php 
  //if((date('Y-m-d', strtotime(mssql_result($consult_fact,0,"fechaemi"))) >= date('Y-m-d', mktime(0,0,0,10,1,2017))) and (date('Y-m-d', strtotime(mssql_result($consult_fact,0,"fechaemi"))) <= date('Y-m-d', mktime(0,0,0,12,31,2017)))){
    $query_por_iva = mssql_query("select MtoTax from SATAXVTA where numerod = '$numerod'");
?>
  <td colspan="4"><div align="right">Impuestos 16% </div></td>
<?php 
  /*}else{
?>
  <td colspan="4"><div align="right">Impuestos 12% </div></td>
<?php 
  }*/
?>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"mtotax")),2, ",", "."); ?> </div></td>
  </tr>
  <tr class="ui-btn-active">
  <td colspan="4"><div align="right">Monto Total </div></td>
  <td><div align="center"><?php echo number_format(rdecimal(mssql_result($consult_fact,0,"mtototal")),2, ",", "."); ?> </div></td>
  </tr>
</table>
<?php 
}else{
echo "SIN DETALLES";
} 
?>	