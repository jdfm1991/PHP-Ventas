<?php 
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
//session_start();
set_time_limit(0);
function normalize_date($date){ //VENESUR
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			 return "$var[2]-$var[1]-$var[0]";
		 }
	}
$fechai = $_GET['fechai']; 
$fechai2 = str_replace('/', '-', $fechai); 
$fechai2 = date('Y-m-d', strtotime($fechai2));

$fechaf = $_GET['fechaf']; 
$fechaf2 = str_replace('/', '-', $fechaf); 
$fechaf2 = date('Y-m-d', strtotime($fechaf2));
$convend = $_GET['codvend'];
$total_fact = 0;

function decimal($val){
return number_format($val, 2, ",", ".");
}
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver_ped(){
location.href = "index.php?page=pedidos_busca_fact&mod=1";
}

</script>
    <h3>Pedidos Facturados al <?php echo $_GET['fechai']; ?> Hasta <?php echo $_GET['fechaf']; ?></h3>
    <div data-role="collapsible-set" data-theme="c" data-content-theme="d">
<?php 
$codvend = $_SESSION['open'];

$presupuesto = $modelo->consultaSQL("select distinct(saitemfac.numerod) as numerod, saitemfac.TipoFac from saitemfac inner join saux on saitemfac.Onumero = saux.numerod INNER JOIN safact on SAFACT.NumeroD=SAITEMFAC.NumeroD where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and saitemfac.codvend = '$convend' and saux.estatus = '1' and NumeroR is null and saitemfac.CodSucu='00000' order by saitemfac.numerod desc");

$ContadorPresupuesto = 0;
foreach ($presupuesto as $row) {
	$ContadorPresupuesto++;
}

if ($ContadorPresupuesto != 0){ 
	$cuenta = $ContadorSaux = 0;
	foreach ($presupuesto as $row) {
		$tipoDocumento='';
		$numerod = $row["numerod"];
		$TipoFac = $row["TipoFac"];
		$saux_clientes = $modelo->consultaSQL("select numerod, coditem, descrip1 as descriprod, cantidad as pedido, precio, esunid as unidad, esexento as esexcento, Onumero, MtoTax, TotalItem from saitemfac where numerod = '$numerod' and (tipofac = 'A' or tipofac = 'c') and saitemfac.CodSucu='00000'");
		foreach ($saux_clientes as $row2) {
			$ContadorSaux++;
			$Onumero = $row2["Onumero"];
		}
		if ($ContadorSaux!=0){
			$cuenta++;
			$safact = $modelo->consultaSQL("select numerod, descrip, fechae, codvend from safact where numerod = '$numerod' and (tipofac = 'A' or tipofac = 'c') and safact.CodSucu='00000'");
			$query_por_iva = $modelo->consultaSQL("select MtoTax from SATAXVTA where numerod = '$numerod' and CodSucu='00000'");

			foreach ($query_por_iva as $row4) {
				$MtoTax = $row4["MtoTax"];
			}

			foreach ($safact as $row1) {
				$descrip = $row1["descrip"];
				$fechae = $row1["fechae"];
				$codvend = $row1["codvend"];
			}
?>
			<div data-role="collapsible">
			<?php

			if($TipoFac=='A'){

				$tipoDocumento='FACT';

			}else{
				if($TipoFac=='C'){

					$tipoDocumento='NE';

				}else{

					$tipoDocumento='';
					
				}

			}

			?>
				<h3><?php echo $tipoDocumento; ?>: <?php echo $numerod; ?>, <?php echo utf8_encode($descrip); ?> FECHA <?php echo $tipoDocumento; ?>:  
				<?php echo date("d/m/Y h:i:s A",strtotime($fechae)); ?>, EVD: <?php echo ($codvend); ?>, PEDIDO: <?php echo $Onumero; ?></h3>
				<p><table  >
	 				<thead class="ui-btn-active" >
					<tr >
					<th  data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
  					<th  data-priority="2"><div align="center"><strong>Pedido</strong></div></th>
  					<th  data-priority="2"><div align="center"><strong>Total</strong></div></th>
					</tr>
						</thead>
					<tbody>
						<tr>
  			<?php 
	  			$base = 0;
	  			$total = 0;
	 			$iva = 0;
 				$por_iva = 0;
				$bult = 0;
				$paq = 0;
			  foreach ($saux_clientes as $row2){ ?>
			<td><div align="left"><?php echo utf8_encode($row2["descriprod"]); ?></div> </td>
	<td><div align="center"><?php 
	echo round($row2["pedido"])." "; 
	if ($row2["unidad"] == 0){
		echo "Paq";
		$bult = $bult + round($row2["pedido"]);
	}else{ 
		echo "Uni";
		$paq = $paq + round($row2["pedido"]);
	} ?></div></td>
	<td><div align="center"><?php 
	echo decimal($row2["pedido"]*$row2["precio"]); 
	$base = $base + $row2["pedido"]*$row2["precio"];
	if ($row2["esexcento"]==0){
		if((date('Y-m-d', strtotime(normalize_date($fechai))) >= date('Y-m-d', mktime(0,0,0,10,1,2017))) and (date('Y-m-d', strtotime(normalize_date($fechai))) <= date('Y-m-d', mktime(0,0,0,12,31,2017)))){
			$por_iva = $MtoTax/100;
			$iva = $iva + (($row2["MtoTax"]));
			$total = $total + (($row2["TotalItem"]+$row2["MtoTax"]));
		}else{
			$iva = $iva + (($row2["MtoTax"]));
			$total = $total + (($row2["TotalItem"]+$row2["MtoTax"]));
		}
	}else{
		$total = $total + (($row2["TotalItem"]));
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
<a href="detalle.php?&numd=<?php echo $numerod; ?>&tipo=<?php echo $TipoFac; ?>" data-rel="dialog">Visualizar Factura</a></td>
					</p>
   				</div>
			<?php
				$total_fact = $total_fact + $total;
			}
		}
			
		?>

</div>

		<?php 	
		echo "<p>TOTAL DE PEDIDOS FACTURADOS: ".$cuenta."</p><p>SUMA TOTAL FACTURAS: ".number_format($total_fact, 2, ',', '.')."</p>";
		}else{
		echo "NO HAY PEDIDOS FACTURADOS";
		}
	  ?>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_ped()">Volver</button></p>
