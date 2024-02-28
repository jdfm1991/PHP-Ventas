<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Ventas_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
set_time_limit(0);
function normalize_date($date){ //VENESUR
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			 return "$var[2]-$var[1]-$var[0]";
		 }
	}
$fechai = $_GET['fechai']; $fechai2 = str_replace('/','-',$fechai); $fechai2 = date('Y-m-d', strtotime($fechai2));
$fechaf = $_GET['fechaf']; $fechaf2 = str_replace('/','-',$fechaf); $fechaf2 = date('Y-m-d', strtotime($fechaf2));
$convend = $_GET['codvend'];
/*$fechai2 = normalize_date($fechai2);
$fechaf2 = normalize_date($fechaf2);*/
if ($convend){

$notas_debitos = mssql_query("select codclie, safact.codvend as code_vendedor, numerod, safact.fechae as fecha_fact, safact.codclie as cod_clie, safact.descrip as cliente, Monto, tipofac, mtototal FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and codvend = '$convend' and (tipofac = 'A' or tipofac = 'B') order by numerod");

}else{
$notas_debitos = mssql_query("select codclie, safact.codvend as code_vendedor, numerod, safact.fechae as fecha_fact, safact.codclie as cod_clie, safact.descrip as cliente, Monto, tipofac, mtototal FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and (tipofac = 'A' or tipofac = 'B') order by numerod");
}
?>

<script type="text/javascript">
function volver_ventas(){
location.href = "index.php?page=ventas&mod=1";
}
</script>
<h3>Ventas por EDV: <?php if ($convend) { echo $convend; }else{ echo "TODOS"; } ?>, Desde: <?php echo $_GET['fechai']; ?> Hasta: <?php echo $_GET['fechaf']; echo " FechaEmisión".date('d-m-Y h:i a',time() - 3600*date('I')); ?> </h3>	
<table width="617" border="0" data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
<thead class="ui-btn-active"> 
 <tr bgcolor="#0099FF">
    <th data-priority="2"><div align="center"><strong>Tipo </strong></div></th>
    <th data-priority="2"><div align="center"><strong>NoOpe  </strong></div></th>
    <th data-priority="3"><div align="center"><strong>Fecha Fact</strong></div></th>
	<th width="74" data-priority="3"><div align="center"><strong>CodClie</strong></div></th>
	<th width="280" data-priority="3"><div align="center"><strong>Cliente</strong></div></th>
    <th width="67" data-priority="6"><div align="center"><strong>Total</strong></div></th>
    </tr>
</thead>
<tbody>
	<?php
	$suma_monto = 0;
	$suma_monto_total = 0;
	$cont_fact = 0;
	$cont_devol = 0;
	for($i=0;$i<mssql_num_rows($notas_debitos);$i++){
		$nfatc = mssql_result($notas_debitos,$i,"numerod");
	?>
   <tr<?php if (($i % 2) != 0){ ?>
   bgcolor="#CCCCCC"
  <?php } ?>>
    <th width="37"><div align="center"><?php 
	if (mssql_result($notas_debitos,$i,"tipofac") == "A"){
	echo "FACT";
	$cont_fact++;
	}else{
	echo "DEVO";
	$cont_devol++;
	}  ?></div></th>
	
    <th width="56"><div align="center"><a href="detalle.php?&numd=<?php echo mssql_result($notas_debitos,$i,"numerod"); ?>&tipo=<?php echo mssql_result($notas_debitos,$i,"tipofac"); ?>" data-rel="dialog" ><?php echo mssql_result($notas_debitos,$i,"numerod"); ?></a></div></th>
    <td width="77"><div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($notas_debitos,$i,"fecha_fact"))); ?></div></td>
	<td><div align="center"><?php echo mssql_result($notas_debitos,$i,"codclie"); ?></div></td>
	   <td><div align="center"><?php echo utf8_encode(mssql_result($notas_debitos,$i,"cliente")); ?></div></td>
	<?php /*?>   <td><div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($notas_debitos,$i,"fechae"))); ?></div></td><?php */?>
  
	   <td><div align="center"><?php
	   if (mssql_result($notas_debitos,$i,"tipofac") == "A"){ 
	   $suma_monto_total = $suma_monto_total + mssql_result($notas_debitos,$i,"Mtototal");
	   }else{
	   $suma_monto_total = $suma_monto_total - mssql_result($notas_debitos,$i,"Mtototal");
	   }
	   echo number_format(mssql_result($notas_debitos,$i,"Mtototal"), 2, ",", "."); ?></div></td>
    </tr>
   <?php } ?>
    <tr>
     <td colspan="5" align="center">Total = </td>
	   <td align="center"><div align="center"><?php echo number_format($suma_monto_total, 2, ",", "."); ?></div></td>
    </tr>
</tbody>
</table>
<tr>
     <td colspan="8"><?php echo "Total de Operaciones: ".mssql_num_rows($notas_debitos).". Total de Facturas: ".$cont_fact.". Total Devoluciones: ".$cont_devol; ?></td>
  </tr>

