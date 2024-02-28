<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
session_start();
set_time_limit(0);
$marca = $_POST['marca'];
$check = $_POST['check'];
$codclie = $_POST['codclie'];

$ContadorProd=$ContadorPadre= $ContadorConsulta=0;

$datos = $modelo->consultaSQL("select tipopvp from saclie where codclie = '$codclie' order by descrip");
$tipopvp =  '';
foreach ($datos as $row) {
	$tipopvp = $row["tipopvp"];
}

$pvp = $tipopvp;
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
$detal = 0;

$consulta = $modelo->consultaSQL("SELECT descrip from sainsta where codinst = '$marca' order by descrip");
foreach ($datos as $row) {
	$ContadorConsulta++;
}

$consulta_padre = $modelo->consultaSQL("SELECT descrip from sainsta where InsPadre = '$marca' order by descrip");
foreach ($datos as $row) {
	$ContadorPadre++;
}

				if ($check == 0){
						// cuando existencia es cero
						$productos = $modelo->consultaSQL("SELECT * from saexis inner join saprod on saexis.codprod = saprod.codprod where (saexis.codubic = '01')  and saprod.codinst = '$marca'  and saexis.CodSucu='00000' and saprod.CodSucu='00000'  order by saprod.codprod");
				}else{
						$productos = $modelo->consultaSQL("SELECT * from saexis inner join saprod on saexis.codprod = saprod.codprod  where (saexis.codubic = '01') and saprod.codinst = '$marca' and saprod.activo <> '0' and  (saexis.existen > 0 or saexis.exunidad > 0) and saexis.CodSucu='00000' and saprod.CodSucu='00000' order by saprod.codprod");
				} 
	
$query_iva = $modelo->consultaSQL("select (1 + MtoTax / 100) MtoTax from sataxes where codtaxs = 'IVA'");
$iva =0;
foreach ($query_iva as $row) {
	$iva = $row["MtoTax"] ;
}

foreach ($productos as $row) {
	$ContadorProd ++;
}

$num = $ContadorProd;
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=productos&mod=1";
}
</script>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
input[type=number] {
   width: 50px;
}
</style>

<strong><?php
$tipo = 1;
$i = 0;
 ?></strong></br>
<form method="post" id="formlista" name="formlista">
<table  data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke" >
     <thead class="ui-btn-active" >
	<tr>
<th  data-priority="2"><div align="center"><strong>Codigo</strong></div></th>
    <th  data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
	 <th data-priority="3"><div align="center"><strong>Unidad</strong></div></th>
	  <th  data-priority="4"><div align="center"><strong>Pedido</strong></div></th>
	</tr>
  </thead>
  <tbody>
  <tr>
  <?php foreach ($productos as $row){ ?>
  	<td><?php echo $row["CodProd"]; ?></td>
  	<td><?php echo "<strong>".utf8_decode($row["Descrip"])."</strong>(".round($row["Existen"]).")"."(".round($row["ExUnidad"]).")"; ?> <?php if ($row["ExUnidad"] > 0 or $row["Existen"] > 0){ ?> <img src="img/si.png" width="15" height="15" border="0"  /> <?php }else{ ?> <img src="img/no.png" width="15" height="15" border="0"  />  <?php } ?> </br> Paq:[
		<?php
	 	if ($row["EsExento"] == 0){
	 		echo number_format(($row["Precio" . $pvp])*$iva, 2, ",", "."); 
		}else{
			echo number_format(($row["Precio" . $pvp]), 2, ",", "."); 
		}
		?>
	]</br> Uni:[
		<?php
	 	if ($row["EsExento"] == 0){
			if ($pvp == 1){
				echo number_format( $row["PrecioU"]*$iva , 2, ",", "."); 
			}else{
				echo number_format( ($row["PrecioU" . $pvp])*$iva, 2, ",", ".");
			}	
		}else{
			if ($pvp == 1){
				echo number_format( $row["PrecioU"] , 2, ",", "."); 
			}else{
				echo number_format( ($row["PrecioU" . $pvp]), 2, ",", ".");
			}	
		}	
		?>
	]
	<input type="hidden" id="codprod<?php echo $i; ?>" name="codprod<?php echo $i; ?>" value="<?php echo $row["CodProd"]; ?>" />
	</td>
	<td>
	  <select name="unidad<?php echo $i; ?>" id="unidad<?php echo $i; ?>">
	  
	   <?php if ($detal == 1){ ?>
		<option value="1" <?php if ($detal == 1){ ?> selected="selected" <?php } ?> >UNID</option>
		<?php }else{ ?>
		<option value="0" selected="selected">PAQ</option>
		<option value="1" >UNID</option>
		<?php } ?>
		
	   </select></td>
	<td><input min="1" name="cant<?php echo $i; ?>" type="number" id="cant<?php echo $i; ?>" onclick="this.select()" size="5"></td>
  </tr>
  <?php $i++; } ?>
  <tr>
  <td colspan="3">
 <input type="hidden" id="codclie" name="codclie" value="<?php echo $codclie; ?>" />
 <input type="hidden" id="num" name="num" value="<?php echo $num; ?>" />
    <div align="center"><a href="javascript:;" class="ui-btn-down-b" onclick="guardar_pedido(<?php echo $num; ?>)">Guardar y Continuar</a>
	</div>
	
	</td>
  </tr>
  </tbody> 
</table>

</form>
Total de Productos <?php echo $num; 
echo "<script language=Javascript>prehide();</script>";

?>