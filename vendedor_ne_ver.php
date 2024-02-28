<?php 
require("conexion.php");
session_start();
$codigo = $_GET['descrip'];
$name = mssql_query("select codvend, descrip from SAVEND where CODVEND = '$codigo'");
$consulta = mssql_query("SELECT saacxc.numerod as factura, saclie.descrip as cliente, saacxc.fechae as fecha, saacxc.FechaV as vencimiento, saacxc.saldo as msaldo, saacxc.codvend as vende, saacxc.tipocxc as oper, saacxc.document as documento, notas1, notas2 from aj_d.dbo.saacxc inner join saclie on saacxc.codclie = saclie.codclie where saacxc.CODVEND='$codigo' and saacxc.saldo > 0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20')");
$num = mssql_num_rows($consulta);
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=vendedor_ne&mod=1";
}
</script>
<?php
if (mssql_num_rows($name)!= 0){ 	
?>	
<label class="ui-btn-active"><strong> EDV : </strong> <?php echo mssql_result($name,0,"codvend").". ".mssql_result($name,0,"descrip"); ?></label>
<?php 
}else{
echo "<p>El Codigo de Vendedor que ha Consultado no Existe en Sistema</p>";
}
?>
<?php
if ($num != 0){ 	
?>	
<p><strong>NE Pendientes por Cobrar</strong></p>	
<div style="width:auto; overflow:scroll;">
<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
     <thead class="ui-btn-active">
	<tr>
	<th data-priority="2"><div align="center"><strong>Oper</strong></div></th>
    <th data-priority="2"><div align="center"><strong>Documento</strong></div></th>
	<th data-priority="3"><div align="center"><strong>Cliente</strong></div></th>
    <th data-priority="3"><div align="center"><strong>Fecha Emi</strong></div></th>
	<th data-priority="5"><div align="center"><strong>Dias Trans</strong></div></th> 
    <th data-priority="5"><div align="center"><strong>Monto</strong></div></th>
  </tr>
  </thead>
  <?php
  $suma = 0; 
  $cont = 0;
  for($i=0;$i<$num;$i++){
  ?>
  <tbody>
  <tr>
  	<th><div align="center"><a onclick="alert('<?php 
	if (mssql_result($consulta,$i,"oper") == "10"){
	echo "Factura Nro: ".mssql_result($consulta,$i,"documento"); 
	}else{
	echo "Nota Debito: ".mssql_result($consulta,$i,"notas1").", ".mssql_result($consulta,$i,"notas2"); 
	}
	?>');" href="javascript:;"><?php if (mssql_result($consulta,$i,"oper") == "10"){ echo "NE"; }else{ echo "N/D"; } ?></a></div></th>
    <th>
    	<div align="center"><?php echo mssql_result($consulta,$i,"factura"); ?>
    	</div>
    </th>
	<td><div align="center"><?php echo utf8_encode(mssql_result($consulta,$i,"cliente")); ?></div></td>
    <td><div align="center"><?php 
	$dateE=mssql_result($consulta,$i,"fecha");
	$dtE = strtotime($dateE); 
	echo date("d/m/Y", $dtE);
	 ?></div></td>
 <?php /*?>   <td><div align="center"><?php 
	$dateV=mssql_result($consulta,$i,"FechaV");
	$dtV = strtotime($dateV); 
	echo date("d/m/Y", $dtV);
	
	?></div></td><?php */?>
	<td><div align="center">
	  <?php 
putenv("TZ=America/Caracas");
$fecha = date("Y-m-d");
$datetime=mssql_result($consulta,$i,"vencimiento");
$dt = strtotime($datetime); 
$nuevav = date("Y-m-d", $dt);



$resultado1 = (int)(strtotime($fecha));
$resultado2 = (int)(strtotime($nuevav));
$resultado3 = $resultado1 - $resultado2; 
$dias = $resultado3 / 60 /60 /24;

echo round($dias);
	
	 ?>
    </div></td>
    <td><div align="center"><?php 
	$suma = mssql_result($consulta,$i,"msaldo") + $suma;
	echo number_format(mssql_result($consulta,$i,"msaldo"), 2, ",", "."); ?> $</div></td>
  </tr>
  <?php 
  $cont++;
  } ?>
 <tr class="ui-state-default">
    <td colspan="6"><strong>Total de Renglones: <?php echo $cont; ?></strong></td>
  </tr>
  <tr class="ui-state-default">
   <td colspan="6"><strong> Monto Pendiente: <?php echo number_format($suma, 2, ",", "."); ?> $</strong></td> 
   </tr>
  </tbody> 
</table>
</div>
</br>
<a href="vendedor_ne_excel.php?&descrip=<?php echo $_GET['descrip']; ?>" target="_blank" > Imprimir en Excel</a> 

	<?php 
}else{
	echo "<p>NO POSEE DOCUEMNTOS PENDIENTES</p>";
	}
?>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
