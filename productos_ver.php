<?php 
require("conexion.php");
session_start();
set_time_limit(0);
$marca = $_GET['marca'];
$tipo = $_GET['tipo'];
$check = $_GET['check'];
if ($_SESSION['open'] == ""){
	echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
/*$consulta = mssql_query("select descrip from sainsta where codinst = '$marca' order by descrip");*/
/*$consulta_padre = mssql_query("select marca from saprod where marca like '$marca' order by marca");*/
if ($check == 1){

	$productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, SAEXIS.Existen, SAEXIS.ExUnidad, SAPROD_02.Precio1_B, SAPROD_02.Precio1_P, SAPROD_02.Precio2_B, SAPROD_02.Precio2_P from saexis inner join saprod on saexis.codprod = saprod.codprod inner join SAPROD_02 on saprod.CodProd = SAPROD_02.CodProd where (saexis.codubic = '01') and (saexis.existen > 0 or saexis.exunidad > 0) and saprod.marca like '$marca' and saprod.activo <> '0' order by saprod.descrip");

}else{

	$productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, SAEXIS.Existen, SAEXIS.ExUnidad, SAPROD_02.Precio1_B, SAPROD_02.Precio1_P, SAPROD_02.Precio2_B, SAPROD_02.Precio2_P from saexis inner join saprod on saexis.codprod = saprod.codprod inner join SAPROD_02 on saprod.CodProd = SAPROD_02.CodProd where (saexis.codubic = '01') and saprod.marca like '$marca' and saprod.activo <> '0' and (saexis.existen = 0 and saexis.ExUnidad = 0) order by saprod.descrip");

}

$num = mssql_num_rows($productos);
?>
<script type="text/javascript">
	function volver(){
		location.href = "index.php?page=productos&mod=1";
	}
</script>
<strong>Productos <?php echo $marca;
if ($tipo == 1){
	echo " (DETAL)";
}else{
	echo " (MAYORISTA)";
}
$query_iva = mssql_query("select MtoTax from sataxes where codtaxs = 'IVA'");
$iva = 1+(mssql_result($query_iva, 0, 'mtotax')/100);
?></strong></br>
Tiene Existencia: <img src="img/si.png" width="15" height="15" border="0"  />&nbsp;&nbsp;&nbsp;&nbsp;	No Tiene Existencia: <img src="img/no.png" width="15" height="15" border="0"  />
<div style="width:auto; overflow:scroll;">
	<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead class="ui-btn-active">
			<tr>
				<th width="58" data-priority="2"><div align="center"><strong>CodProd</strong></div></th>
				<th width="52" data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
				<th width="48" data-priority="3"><div align="center"><strong>Exist Bulto</strong></div></th>
				<th width="64" data-priority="3"><div align="center"><strong>Precio Bulto </strong></div></th>
				<th width="74" data-priority="5"><div align="center"><strong>Exist Paq </strong></div></th> 
				<th width="45" data-priority="5"><div align="center"><strong>Precio Paq </strong></div></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php for($i=0;$i<$num;$i++){ ?>
					<th><div align="center"><?php echo mssql_result($productos,$i,"codprod"); ?></div></th>
					<th><div align="left"><?php echo utf8_encode(mssql_result($productos,$i,"descrip")); ?></div>
						<div align="left"></div></th>

						<?php if ($_SESSION['open']){ ?> 
							<td><div align="center"><?php echo round(mssql_result($productos,$i,"existen")); ?> <?php if (mssql_result($productos,$i,"existen") > 0){ ?> <img src="img/si.png" width="15" height="15" border="0"  /> <?php }else{ ?> <img src="img/no.png" width="15" height="15" border="0"  />  <?php } ?></div></td>
						<?php }else{ ?>
							<td><div align="center"><?php echo round(mssql_result($productos,$i,"existen")); ?></div></td>
						<?php } ?>

						<?php if ($tipo == 1){ ?>
							<td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio1_B"), 2, ",", "."); ?> $</div></td>
						<?php }else{ ?>
							<td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio2_B"), 2, ",", "."); ?> $</div></td>
						<?php } ?>

						<?php if ($_SESSION['open']){ ?> 
							<td><div align="center"><?php echo round(mssql_result($productos,$i,"exunidad")); ?> <?php if (mssql_result($productos,$i,"exunidad") > 0){ ?> <img src="img/si.png" width="15" height="15" border="0"  /> <?php }else{ ?> <img src="img/no.png" width="15" height="15" border="0"  />  <?php } ?></div></td>
						<?php }else{ ?>
							<td><div align="center"><?php echo round(mssql_result($productos,$i,"exunidad")); ?></div></td>
						<?php } ?>

						<?php if ($tipo == 1){ ?>
							<td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio1_P"), 2, ",", "."); ?> $</div></td>
						<?php }else{ ?>
							<td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio2_P"), 2, ",", "."); ?> $</div></td>

						<?php } ?>
					</tr>
				<?php } ?>
			</tbody> 
		</table>
	</div>
	Total de Productos <?php echo $num; ?>
</br>
<!-- <a href="productos_excel.php?&marca=<?php //echo $_GET['marca']; ?>&tipo=<?php //echo $_GET['tipo']; ?>&check=<?php //echo $_GET['check']; ?>" target="_blank" > Imprimir en Excel</a> -->


<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>

