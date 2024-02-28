<?php 
require("conexion.php");
session_start();
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

/*$fechai2 = normalize_date($fechai2);
$fechaf2 = normalize_date($fechaf2);*/
function decimal($val){
return number_format($val, 2, ",", ".");
}
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver_ped(){
location.href = "index.php?page=relacionEDV_busca&mod=1";
}
function ver_clie(code){
location.href = "index.php?page=clientes_det&mod=1&codclie="+code+"&descri=<?php echo $descip; ?>";
}
</script>
    <h3>Relacion EDV al <?php echo $_GET['fechai']; ?> Hasta <?php echo $_GET['fechaf']; ?></h3>
    <div data-role="collapsible-set" data-theme="c" data-content-theme="d">
<?php 
$codvend = $_SESSION['open'];
if ($codvend != "99"){
	//$presupuesto = mssql_query("select distinct(saitemfac.numerod) as numerod, TipoFac from saitemfac inner join saux on saitemfac.Onumero = saux.numerod where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and saitemfac.codvend = '$convend' and saux.estatus = '1' order by saitemfac.numerod desc");
	$presupuesto = mssql_query("select distinct(saitemfac.numerod) as numerod, saitemfac.TipoFac from saitemfac inner join saux on saitemfac.Onumero = saux.numerod INNER JOIN safact on SAFACT.NumeroD=SAITEMFAC.NumeroD where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and saitemfac.codvend = '$convend' and saux.estatus = '1' and NumeroR is null order by saitemfac.numerod desc");
}else{
	if ($convend){
		//$presupuesto = mssql_query("select distinct(numerod) as numerod, TipoFac from saitemfac where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and Onumero is not null and Otipo = 'F' and (tipofac = 'A' or tipofac = 'c')  and codvend = '$convend' order by numerod desc");
		$presupuesto = mssql_query("select distinct(saitemfac.numerod) as numerod, saitemfac.TipoFac from saitemfac INNER JOIN safact on SAFACT.NumeroD=SAITEMFAC.NumeroD where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and saitemfac.Onumero is not null and saitemfac.Otipo = 'F' and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'c')  and saitemfac.codvend = '$convend' and NumeroR is null order by numerod desc");
	}else{
		$presupuesto = mssql_query("select distinct(numerod) as numerod, TipoFac from saitemfac where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.fechae)) between '$fechai2' and '$fechaf2' and Onumero is not null and Otipo = 'F' and (tipofac = 'A' or tipofac = 'c') order by numerod desc");
	}
}

if (mssql_num_rows($presupuesto) != 0){ 
	$cuenta = 0;
	for($j=0;$j<mssql_num_rows($presupuesto);$j++){
		$tipoDocumento='';
		$numerod = mssql_result($presupuesto,$j,"numerod");
		$TipoFac = mssql_result($presupuesto,$j,"TipoFac");
		$saux_clientes = mssql_query("select numerod, coditem, descrip1 as descriprod, cantidad as pedido, precio, esunid as unidad, esexento as esexcento, Onumero, MtoTax, TotalItem from saitemfac where numerod = '$numerod' and (tipofac = 'A' or tipofac = 'c')  and OTipo not in ('E') and codvend='$convend' ");

		if (mssql_num_rows($saux_clientes)!=0){
			$cuenta++;

			$Onumerod = mssql_result($saux_clientes,0,"Onumero");
			$saux_pedido = mssql_query("select TipoFac, OTipo, numerod, ONumero, coditem, descrip1 as descriprod, cantidad as pedido, precio, esunid as unidad, esexento as esexcento, Onumero, MtoTax, TotalItem from saitemfac where (numerod = '$Onumerod' or ONumero='$Onumerod' ) and (tipofac = 'A' or tipofac = 'c' or  tipofac = 'F') and OTipo not in ('E')");

			$saux_pedidoFecha = mssql_query("SELECT distinct fecha FROM SAUX where numerod = '$Onumerod' order by fecha desc");

			$fechaPedido = date('d/m/Y h:i:s A', strtotime(mssql_result($saux_pedidoFecha,0,"fecha")));

			$safact = mssql_query("select numerod, descrip, fechae, codvend from safact where numerod = '$numerod' and (tipofac = 'A' or tipofac = 'c') and OTipo not in ('E') and codvend='$convend'");
			$query_por_iva = mssql_query("select MtoTax from SATAXVTA where numerod = '$numerod'");

			$fechaFacturado =date("d/m/Y h:i:s A",strtotime(mssql_result($safact,0,"fechae")));
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
				<h3><?php echo $tipoDocumento; ?>: <?php echo $numerod; ?>, <?php echo utf8_encode(mssql_result($safact,0,"descrip")); ?> FECHA <?php echo $tipoDocumento; ?>:  
				<?php echo date("d/m/Y h:i:s A",strtotime(mssql_result($safact,0,"fechae"))); ?>, EDV: <?php echo $convend; ?>, PEDIDO: <?php echo mssql_result($saux_clientes,0,"Onumero"); ?></h3>
				<p><table  >
				PEDIDO&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fechaPedido; ?>
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
	  			for($i=0;$i<mssql_num_rows($saux_pedido);$i++){ ?>
			<td><div align="left"><?php echo utf8_encode(mssql_result($saux_pedido,$i,"descriprod")); ?></div> </td>
				<td><div align="center"><?php 
				echo round(mssql_result($saux_pedido,$i,"pedido"))." "; 
				if (mssql_result($saux_pedido,$i,"unidad") == 0){
					echo "Paq";
					$bult = $bult + round(mssql_result($saux_pedido,$i,"pedido"));
				}else{ 
					echo "Uni";
					$paq = $paq + round(mssql_result($saux_pedido,$i,"pedido"));
				} ?></div></td>
				<td><div align="center"><?php 
				echo decimal(mssql_result($saux_pedido,$i,"pedido")*mssql_result($saux_pedido,$i,"precio")); 
				$base = $base + mssql_result($saux_pedido,$i,"pedido")*mssql_result($saux_pedido,$i,"precio");
				if (mssql_result($saux_pedido,$i,"esexcento")==0){
					/*if((date('Y-m-d', strtotime(normalize_date($fechai))) >= date('Y-m-d', mktime(0,0,0,10,1,2017))) and (date('Y-m-d', strtotime(normalize_date($fechai))) <= date('Y-m-d', mktime(0,0,0,12,31,2017)))){
						$por_iva = mssql_result($query_por_iva,0,"MtoTax")/100;
						//$iva = $iva + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*$por_iva);
						$iva = $iva + ((mssql_result($saux_pedido,$i,"MtoTax")));
						//$total = $total + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*(1+$por_iva));
						$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")+mssql_result($saux_pedido,$i,"MtoTax")));
					}else{*/
						//$iva = $iva + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*0.16);
						$iva = $iva + ((mssql_result($saux_pedido,$i,"MtoTax")));
						//$total = $total + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*1.16);
						$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")+mssql_result($saux_pedido,$i,"MtoTax")));
					//}
				}else{
					$total = $total + ((mssql_result($saux_pedido,$i,"TotalItem")));
				} 
				?></div></td>
			</tr>
			<?php } 
			
			$iva = $base * 0.16;

			$total = $base + $iva;
			
			?>
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
					
				<br><br><br>
				
				
				<table  >
				FACTURADO&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fechaFacturado; ?>
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
	  			for($i=0;$i<mssql_num_rows($saux_clientes);$i++){ ?>
			<td><div align="left"><?php echo utf8_encode(mssql_result($saux_clientes,$i,"descriprod")); ?></div> </td>
	<td><div align="center"><?php 
	echo round(mssql_result($saux_clientes,$i,"pedido"))." "; 
	if (mssql_result($saux_clientes,$i,"unidad") == 0){
		echo "Paq";
		$bult = $bult + round(mssql_result($saux_clientes,$i,"pedido"));
	}else{ 
		echo "Uni";
		$paq = $paq + round(mssql_result($saux_clientes,$i,"pedido"));
	} ?></div></td>
	<td><div align="center"><?php 
	echo decimal(mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio")); 
	$base = $base + mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio");
	if (mssql_result($saux_clientes,$i,"esexcento")==0){
		if((date('Y-m-d', strtotime(normalize_date($fechai))) >= date('Y-m-d', mktime(0,0,0,10,1,2017))) and (date('Y-m-d', strtotime(normalize_date($fechai))) <= date('Y-m-d', mktime(0,0,0,12,31,2017)))){
			$por_iva = mssql_result($query_por_iva,0,"MtoTax")/100;
			//$iva = $iva + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*$por_iva);
			$iva = $iva + ((mssql_result($saux_clientes,$i,"MtoTax")));
			//$total = $total + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*(1+$por_iva));
			$total = $total + ((mssql_result($saux_clientes,$i,"TotalItem")+mssql_result($saux_clientes,$i,"MtoTax")));
		}else{
			//$iva = $iva + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*0.16);
			$iva = $iva + ((mssql_result($saux_clientes,$i,"MtoTax")));
			//$total = $total + ((mssql_result($saux_clientes,$i,"pedido")*mssql_result($saux_clientes,$i,"precio"))*1.16);
			$total = $total + ((mssql_result($saux_clientes,$i,"TotalItem")+mssql_result($saux_clientes,$i,"MtoTax")));
		}
	}else{
		$total = $total + ((mssql_result($saux_clientes,$i,"TotalItem")));
	} 
	?></div></td>
  </tr>
  <?php } 
 
 		$iva = $base * 0.16;

	    $total = $base + $iva;
  ?>
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
<a href="detalle_excel_relacion.php?&numd=<?php echo $numerod; ?>&tipo=<?php echo $TipoFac; ?>&codvend=<?php echo $convend; ?>" target="_blank" > Imprimir en Excel</a></td>
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
		echo "NO HAY PEDIDOS";
		}
	  ?>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_ped()">Volver</button></p>
