<?php 
require("conexion.php");
session_start();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
$codigo = $_GET['codclie'];
$descri = $_GET['descri'];
$name = mssql_query("select * from SaClie where codclie = '$codigo'");
$consulta = mssql_query("select * from aj_d.dbo.saacxc where codclie='$codigo' and tipocxc='10' and saldo>0");
$num = mssql_num_rows($consulta);
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=clientes&mod=1&descrip=<?php echo $descri; ?>";
}
</script>
<table  width="auto" class="ui-btn-active" border="0">
  <tr>
    <td><strong>Cliente:</strong> <?php echo utf8_encode(mssql_result($name,0,"Descrip"))." ".mssql_result($name,0,"ID3"); ?></td>
  </tr>
  <tr class="ui-state-default">
    <td><strong>Dir: </strong> <?php echo utf8_encode(mssql_result($name,0,"Direc1"))." ".mssql_result($name,0,"Direc2"); ?></td>
  </tr>
  <tr class="ui-state-default">
    <td><strong>Tef: </strong> <?php echo utf8_encode(mssql_result($name,0,"Telef"))." ".mssql_result($name,0,"Movil"); ?></td>
  </tr>
  <tr>
    <td><?php echo "<strong>Dias de Credito: </strong>".mssql_result($name,0,"DiasCred")."   <strong>Limite Credito: </strong>".mssql_result($name,0,"LimiteCred")."   <strong>Descuento %: </strong>".mssql_result($name,0,"Descto"); ?></td>
  </tr>
  <tr>
    <td><?php echo "<strong>Ultima Venta: </strong>".mssql_result($name,0,"NumeroUV")."  <strong>Monto: </strong>".mssql_result($name,0,"MontoUV")."  <strong>Fecha: </strong>".mssql_result($name,0,"FechaUV"); ?></td>
  </tr>
  <tr>
    <td><?php echo "<strong>Ultimo Pago: </strong>".mssql_result($name,0,"NumeroUP")."   <strong>Monto: </strong>".mssql_result($name,0,"MontoUP")."</strong>   <strong>Fecha: </strong>".mssql_result($name,0,"FechaUP"); ?></td>
  </tr>
</table>
<?php
if ($num != 0){ 	
?>	
<strong>Facturas Pendientes por Cobrar	</strong>
<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
     <thead class="ui-btn-active">
	<tr>
    <th data-priority="2"><div align="center"><strong>Nro Fact</strong></div></th>
	<th data-priority="3"><div align="center"><strong>CodVend</strong></div></th>
    <th data-priority="3"><div align="center"><strong>Fecha Emi</strong></div></th>
   <!-- <th data-priority="4"><div align="center"><strong>Fecha Venci</strong></div></th>-->
	<th data-priority="5"><div align="center"><strong>Dias Trans</strong></div></th> 
    <th data-priority="6"><div align="center"><strong>Monto</strong></div></th>
  </tr>
  </thead>
  <?php
  $suma = 0; 
  $cont = 0;
  for($i=0;$i<$num;$i++){
  ?>
  <tbody>
  <tr>
    <th><div align="center"><a href="detalle.php?&numd=<?php echo mssql_result($consulta,$i,"NumeroD"); ?>&tipo=A" data-rel="dialog"><?php echo mssql_result($consulta,$i,"NumeroD"); ?></a></div></th>
	<td><div align="center"><?php echo mssql_result($consulta,$i,"CodVend"); ?></div></td>
    <td><div align="center"><?php 
	$dateE=mssql_result($consulta,$i,"FechaE");
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
$datetime=mssql_result($consulta,$i,"FechaV");
$dt = strtotime($datetime); 
$nuevav = date("Y-m-d", $dt);



$resultado1 = (int)(strtotime($fecha));
$resultado2 = (int)(strtotime($nuevav));
$resultado3 = $resultado1 - $resultado2; 
$dias = $resultado3 / 60 /60 /24;

echo $dias;
	
	 ?>
    </div></td>
    <td><div align="center"><?php 
	$suma = mssql_result($consulta,$i,"Saldo") + $suma;
	echo number_format(mssql_result($consulta,$i,"Saldo"), 2, ",", "."); ?></div></td>
  </tr>
  <?php 
  $cont++;
  } ?>
 <tr class="ui-state-default">
    <td colspan="5"><strong>Total de Renglones: <?php echo $cont; ?></strong></td>
  </tr>
  <tr class="ui-state-default">
   <td colspan="5"><strong> Monto Pendiente: <?php echo number_format($suma, 2, ",", "."); ?></strong></td> 
   </tr>
  </tbody> 
</table>
</br>
<a href="clientes_excel.php?&codclie=<?php echo $_GET['codclie']; ?>&descri=<?php echo $_GET['descri']; ?>" target="_blank" > Imprimir en Excel</a>

	<?php 
}else{
	echo "NO POSEE FACTURAS PENDIENTES";
	}
?>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>