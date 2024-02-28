<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
//session_start();
$descip = $_GET['descrip'];
function decimal($val){
return number_format($val, 2, ",", ".");
}
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver_ped(){
location.href = "index.php?page=pedidos&mod=1";
}
function ver_clie(code){
location.href = "index.php?page=clientes_det&mod=1&codclie="+code+"&descri=<?php echo $descip; ?>";
}
</script>
    <h3>Pedidos en Espera </h3>
<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
<?php 
$codvend = $_SESSION['open'];

$datos_clientes = $modelo->consultaSQL("select distinct(codclie) from saux where codvend = '$codvend' and estatus = '0'");
$Contadorclie = 0;
foreach ($datos_clientes as $row) {
	$Contadorclie++;
}

	  	if (($Contadorclie) != 0){

			foreach ($datos_clientes as $row) {
			$codclie = $row["codclie"];
			$ver_clie = $modelo->consultaSQL("select codclie, descrip from saclie where codclie = '$codclie' and CodSucu='00000'");
			   foreach ($ver_clie as $rowClie) {?>
				<div data-role="collapsible">
					<h3><?php echo utf8_encode($rowClie["descrip"]); ?></h3>
					<?php }
					$saux_clientes = $modelo->consultaSQL("select descriprod,unidad,pedido,id_pedido,precio,esexcento from saux where codclie = '$codclie' and estatus = 0 order by id_pedido desc");
					?>
					<p><table  >
		 				<thead class="ui-btn-active" >
						<tr >
    					<th  data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
	  					<th  data-priority="2"><div align="center"><strong>Pedido</strong></div></th>
	  					<th  data-priority="2"><div align="center"><strong>Total</strong></div></th>
	  					<!--<th  data-priority="2"><div align="center"><strong>Cancel</strong></div></th>-->
						</tr>
  						</thead>
						<tbody>
  						<tr>
  			<?php 
		  			$base = 0;
		  			$total = 0;
		 			$iva = 0;
					$bult = 0;
  				    $paq = 0;
			  		foreach ($saux_clientes as $rowSaux){ ?>
				<td><div align="left"><?php echo utf8_encode( $rowSaux["descrip"]); ?></div> </td>
	<td><div align="center"><?php 
	echo round($rowSaux["pedido"])." "; 
	if ($rowSaux["unidad"] == 0){
		echo "Bult";
		$bult = $bult + round($rowSaux["pedido"]);
	}else{ 
		echo "Paq";
		$paq = $paq + round($rowSaux["pedido"]);
	} ?></div></td>
	<td><div align="center"><?php 
	echo decimal($rowSaux["pedido"]*$rowSaux["precio"]); 
	$base = $base + $rowSaux["pedido"]*$rowSaux["precio"];
	if ($rowSaux["esexcento"]==0){
	$total = $total + (($rowSaux["pedido"]*$rowSaux["precio"])*1.12);
	$iva = $iva + (($rowSaux["pedido"]*$rowSaux["precio"])*0.12);
	}else{
	$total = $total + ($rowSaux["pedido"]*$rowSaux["precio"]);
	} 
	?></div></td>
  </tr>
  <?php } ?>
   <tr>
   <td>Total Bult: <?php echo $bult; ?></td>
  <td> <div align="right">Base Imp</div></td>
	<td ><div align="center"><strong><?php echo decimal($base); ?></strong></div></td>
	</tr>
	<tr>
	<td>Total Paq: <?php echo $paq; ?></td>
  <td> <div align="right">Iva</div></td>
	<td ><div align="center"><strong><?php echo decimal($iva); ?></strong></div></td>
	</tr>
	<tr>
	<td>-</td>
	<td> <div align="right">Total</div></td>
	<td ><div align="center"><strong><?php echo decimal($total); ?></strong></div></td>
	
	</tr>
  </tbody> 
</table>
					</p>
   				</div>
			
			<?php 
			}
		?>

</div>

		<?php 	
		}else{
		echo "NO HAY PEDIDOS EN ESPERA";
		}
	  ?>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_ped()">Volver</button></p>