<?php
require_once("conexion.php");
require("modelo.php");
$modelo = new Modelo();
//session_start();
set_time_limit(0);
if ($_SESSION['open'] == "") {
	echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
function rdecimal($number, $precision = 2, $separator = '.')
{
	$numberParts = explode($separator, $number);
	$response = $numberParts[0];
	if (count($numberParts) > 1) {
		$response .= $separator;
		$response .= substr(
			$numberParts[1],
			0,
			$precision
		);
	}
	return $response;
}

function rdecimal_cantidad($valor)
{
	$float_redondeado = round($valor * 100) / 100;
	return $float_redondeado;
}

$ruta = $_SESSION['open'];
$edv = $modelo->consultaSQL("SELECT Descrip, clase from SAVEND where CodVend = '$ruta'");

$clientes_f = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT b.CodClie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip as ciudad , (b.MtoTotal/b.Factor) as total , a.codvend from SAITEMFAC as a inner join SAFACT as b on a.NumeroD = b.numerod inner join SACLIE as c on b.CodClie = c.CodClie inner join SACIUDAD as d on c.Ciudad =d.Ciudad where  a.TipoFac in ('F') and b.TipoFac in ('F') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf
	and a.CodVend ='$ruta' and a.CodSucu='00000' group by b.Codclie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip , b.MtoTotal, b.Factor,a.codvend, b.numerod order by a.numerod
	");

$clientes_a_c = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT b.CodClie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip as ciudad , (b.MtoTotal/b.Factor) as total , a.codvend from SAITEMFAC as a inner join SAFACT as b on a.NumeroD = b.numerod inner join SACLIE as c on b.CodClie = c.CodClie inner join SACIUDAD as d on c.Ciudad =d.Ciudad where  a.TipoFac in ('C','A') and b.TipoFac in ('C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf
	and a.CodVend ='$ruta' and a.CodSucu='00000' group by b.Codclie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip , b.MtoTotal, b.Factor,a.codvend, b.numerod order by a.numerod
	");

$clientes_detalles = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT CodItem, Descrip1 from
	SAITEMFAC where  TipoFac in ('F','C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and CodVend ='$ruta' and CodSucu='00000' group by  CodItem, Descrip1
	");


$bultos = 0;
$paquetes = 0;
$bultos1 = 0;
$paquetes1 = 0;
$tbultos = 0;
$tpaquetes = 0;
$ttotal = 0;

$tbultos_f = 0;
$tpaquetes_f = 0;
$ttotal_f = 0;

$tbultos_a_c = 0;
$tpaquetes_a_c = 0;
$ttotal_a_c = 0;
//-------------------------------------------------------------
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuelaa
date_default_timezone_set('America/Caracas');
$time = date("d-m-Y h:i:s a");
$actual = date("d/m/Y");
$day = date("l");
switch ($day) {
	case "Sunday":
		$days = "Domingo";
		break;
	case "Monday":
		$days = "Lunes";
		break;
	case "Tuesday":
		$days = "Martes";
		break;
	case "Wednesday":
		$days = "Miercoles";
		break;
	case "Thursday":
		$days = "Jueves";
		break;
	case "Friday":
		$days = "Viernes";
		break;
	case "Saturday":
		$days = "Sabado";
		break;
}
?>
<script type="text/javascript">
	function volver_inicio() {
		location.href = "index.php";
	}	
</script>
<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<thead>
		<?php
		$empresa = $modelo->consultaSQL("SELECT Descrip FROM SACONF where CodSucu='00000'");

		foreach ($empresa as $row) { ?>
			<tr>
				<td style="width:150px;">
					<br><br>
					<!--<img src="img/logo.png" alt="" width="142" height="70" border="0" />-->
				</td>
				<td style="width:400px;text-align: center;">
					<br><br>
					<strong>
						<?php echo $row['Descrip']; ?>
					</strong>
					<h4>Pedidos Realizados
						<?php echo $time; ?>
					</h4>
					<br>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td height="21" colspan="75">
				<hr>
			</td>
		</tr>
		<tr>
			<?php
			foreach ($edv as $row) { ?>
				<td height="21" colspan="25"><strong>Vendedor: </strong>
					<?php echo $ruta; ?>
					<?php echo utf8_decode($row['Descrip']); ?>
				</td>
				<td height="21" colspan="10"><strong>Canal: </strong>
					<?php echo $row['clase']; ?>
				</td>
				<td height="21" colspan="5"><strong> </strong></td>
			<?php } ?>
		</tr>
		<tr>
			<td height="21" colspan="5"><strong>Fecha: </strong>
				<?php echo $actual; ?>
			</td>
		</tr>
		<tr>
			<td height="21" colspan="25"><strong>Dia: </strong>
				<?php echo $days ?>
			</td>
		</tr>
		<tr>
			<td height="12" colspan="75" style="text-align: center;"><strong>
					<h1>Gestión Diaria</h1>

				</strong></td>
		</tr>
		<tr>
			<td height="21" colspan="75" style="text-align: center;"><strong>
					<h2>Presupuestos</h2>
				</strong></td>
		</tr>
		<tr>
			<td height="21" colspan="75" style="text-align: right; color: red;"><strong>
					<h1></h1>
				</strong></td>
		</tr>
	</thead>
</table>

<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<!-- CABECERA DE TABLA     -->
	<thead style="background-color: #17A2B8;color: white;">
		<tr class="ui-widget-header">
			<th class="ui-widget-header">
				<div align="center"># Documento </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Documento </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Codigo Cliente </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Razón Social </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Ciudad </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Paquetes </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Unidades </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Total $</div>
			</th>
		</tr>
	</thead>
	<!-- FIN DE CABECERA DE TABLA  -->
	<tbody>
		<?php
		$numerod = $tipofac = $edv = '';
		foreach ($clientes_f as $row)  {

			$numerod =$row['NumeroD'];
			$tipofac = $row['TipoFac']; 
			$edv = $row['codvend'];

			$bultos = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('F') and EsUnid = 0 and  CodVend ='$ruta' and CodSucu='00000'");
			foreach ($bultos as $rowb)  {
				$cantidadB= $rowb['cantidad'];
			}
			$paquetes = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('F') and EsUnid = 1 and CodVend ='$ruta' and CodSucu='00000'");
			foreach ($paquetes as $rowp) {
					$cantidadP=$rowp['cantidad'];
			}
			
			$tbultos_f = $tbultos_f + $cantidadB;
			$tpaquetes_f = $tpaquetes_f + $cantidadP;
			$ttotal_f = $ttotal_f + $row['total'];
			?>
			<tr>
				<td style="text-align: center;">
					<?php echo $row['NumeroD']; ?>
				</td>
				<td style="text-align: center;">
					<?php
					$tipofac = $row['TipoFac'];
					echo ($tipofac == 'F')
						? "Presupuesto"
						: "";
					?>
				</td>
				<td style="text-align: center;">
					<?php echo $row['CodClie']; ?>
				</td>
				<td style="text-align: left;">
					<?php echo utf8_decode($row['Descrip']); ?>
				</td>
				<td style="text-align: center;">
					<?php echo utf8_decode($row['ciudad']); ?>
				</td>
				<td style="text-align: center;">
					<?php echo rdecimal_cantidad($cantidadB); ?>
				</td>
				<td style="text-align: center;">
					<?php echo rdecimal_cantidad($cantidadP); ?>
				</td>
				<td style="text-align: right">
					<?php echo number_format($row['total'],2); ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<td height="20" colspan="8">
				<hr>
			</td>
		</tr>
		<tr>
			<?php

			$pedidos = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				select  COUNT(descrip) cuenta from SAFACT where  TipoFac in ('F') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf
				and CodVend ='$ruta' and CodSucu='00000' ");

			foreach ($pedidos as $row78) {
				$cpedidos = $row78['cuenta'];
			}

			$maestro = $modelo->consultaSQL("SELECT count(CodClie) cuenta from  saclie  where CodVend ='$ruta' and Activo = '1' and CodSucu='00000'");

			foreach ($maestro as $row78) {
				$cdia = $row78['cuenta'];
			}


			$clientesactivoss = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				select  count(DISTINCT Descrip) cuenta from SAFACT where TipoFac in ('F') and CodVend ='$ruta' and CodSucu='00000' and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf");

			foreach ($clientesactivoss as $row79) {
				$cact = $row79['cuenta'];
			}


			//EFECTIVIDAD BASADA EN LA CANTIDAD DE CLIENTES ACTIVADOS. (CLIENTES ACTIVADOS/CLIENTES DEL DIA * 100 = %) ESTE CASO APLICADA CUANDO SE HACEN MAS DE UN PEDIDO A UN CLIENTE EL MISMO DIA (CASO FRIO / SECO PARLAMAR)
			
			$tefectividad = 0; 
			if ($cpedidos > 0 and $cdia != 0) {
				$tefectividad = ($cpedidos / $cdia) * 100;
			} else {
				$tefectividad = 0;
			}

			?>
		<tr>
			<td height="1" colspan="4" style="text-align: right;"><strong>
					<?php echo "Cantidad de Pedidos:    " . $cpedidos; ?>&nbsp;&nbsp;&nbsp;&nbsp;
				</strong>
			</td>
			<td height="15" colspan="1" style="text-align: right;"><strong>Total: </strong></td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tbultos_f;
				$tbultos += $tbultos_f; ?>
			</td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tpaquetes_f;
				$tpaquetes += $tpaquetes_f; ?>
			</td>
			<td height="1" colspan="1" style="text-align: right;">
				<?php echo rdecimal($ttotal_f);
				$ttotal += $ttotal_f; ?>
			</td>
		</tr>
		<tr>
			<td height="1" colspan="4" style="text-align: right;"><strong>
					<?php echo "Clientes del Dia:    " . $cdia; ?>&nbsp;&nbsp;&nbsp;&nbsp;
				</strong>
			</td>
		</tr>
		<tr>
			<td height="1" colspan="4" style="text-align: right;"><strong>
					<?php echo "Clientes Activados:    " . $cact; ?>&nbsp;&nbsp;&nbsp;&nbsp;
				</strong>
			</td>
		</tr>
		<tr>
			<td height="1" colspan="4" style="text-align: right;"><strong>
					<?php echo "Efectivdad:    " . rdecimal($tefectividad); ?> %
				</strong>
			</td>
		</tr>
		</tr>
		<tr>
			<td height="15" colspan="6" style="text-align: center;"></td>
		</tr>
	</tfoot>
</table>
<br>

<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<thead>
		<tr>
			<td height="21" colspan="75" style="text-align: center;"><strong>
					<h2>Nota de entrega y Factura</h2>
				</strong></td>
		</tr>
		<tr>
			<td height="21" colspan="75" style="text-align: right; color: red;"><strong>
					<h1></h1>
				</strong></td>
		</tr>
	</thead>
</table>

<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<!-- CABECERA DE TABLA     -->
	<thead style="background-color: #17A2B8;color: white;">
		<tr class="ui-widget-header">
			<th class="ui-widget-header">
				<div align="center"># Documento </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Documento </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Codigo Cliente </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Razón Social </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Ciudad </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Paquetes </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Unidades </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Total $</div>
			</th>
		</tr>
	</thead>
	<!-- FIN DE CABECERA DE TABLA  -->
	<tbody>
		<?php foreach ($clientes_a_c as $row80) {

			$numerod = $row80['NumeroD'];
			$tipofac = $row80['TipoFac']; 
			$edv = $row80['Codvend'];

			$bultos = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('C','A') and EsUnid = 0 and  CodVend ='$ruta' and CodSucu='00000'");

			foreach ($bultos as $row81) {
					$cantidadB=$row81['cantidad'];
			}
			
			$paquetes = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('C','A') and EsUnid = 1 and CodVend ='$ruta' and CodSucu='00000'");

			foreach ($paquetes as $row82) {
					$cantidadP=$row82['cantidad'];
			}


			$tbultos_a_c = $tbultos_a_c + $cantidadB;
			$tpaquetes_a_c = $tpaquetes_a_c + $cantidadP;
			$ttotal_a_c = $ttotal_a_c + $row80['total'];

?>
			<tr>
				<td style="text-align: center;">
					<?php echo $row80['numerod']; ?>
				</td>
				<td style="text-align: center;">
					<?php $tipofac = $row80['tipofac'];
					switch ($tipofac) {
						case "C":
							$tipo = "Nota de Entrega";
							break;
						case "A":
							$tipo = "Factura";
							break;

					}
					echo $tipo;
					; ?>
				</td>
				<td style="text-align: center;">
					<?php echo $row80['CodClie']; ?>
				</td>
				<td style="text-align: left;">
					<?php echo utf8_decode($row80['Descrip']); ?>
				</td>
				<td style="text-align: center;">
					<?php echo utf8_decode($row80['ciudad']); ?>
				</td>
				<td style="text-align: center;">
					<?php echo rdecimal_cantidad($cantidadB); ?>
				</td>
				<td style="text-align: center;">
					<?php echo rdecimal_cantidad($cantidadP); ?>
				</td>
				<td style="text-align: right">
					<?php echo number_format($row80['total'],2); ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<td height="20" colspan="8">
				<hr>
			</td>
		</tr>
		<tr>
		<tr>
			<td height="15" colspan="5" style="text-align: right;"><strong>Total: </strong></td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tbultos_a_c;
				$tbultos += $tbultos_a_c; ?>
			</td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tpaquetes_a_c;
				$tpaquetes += $tpaquetes_a_c; ?>
			</td>
			<td height="1" colspan="1" style="text-align: right;">
				<?php echo number_format($ttotal_a_c,2);
				$ttotal += $ttotal_a_c; ?>
			</td>
		</tr>
		</tr>
		<tr>
			<td height="15" colspan="6" style="text-align: center;"></td>
		</tr>
	</tfoot>
</table>
<br>
<!-- tabla para resumen por marca -->
<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<!-- CABECERA DE TABLA     -->
	<thead>
		<tr>
			<td height="21" colspan="75" style="text-align: center;"><strong>
					<h1>Gestión por Marca</h1>
				</strong></td>
		</tr>
		<tr class="ui-widget-header">
			<th class="ui-widget-header">
				<div align="center">Marca </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Paquetes </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Unidades </div>
			</th>
		</tr>
	</thead>
	<!-- FIN DE CABECERA DE TABLA  -->
	<tbody>
		<?php

		$tbultos = $tpaquetes=0;

		$marcasactivas = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			select DISTINCT(b.Marca) as marca from SAITEMFAC as a inner join SAPROD as b on a.CodItem = b.CodProd where a.TipoFac in ('F','C','A') and
			DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf and a.CodVend ='$ruta' and a.CodSucu='00000' and Marca is not null");


		foreach ($marcasactivas as $row83) {

			$marca =$row83["marca"];

			$itemxmarcas = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				SELECT saprod.Marca, sum((CASE WHEN EsUnid = '0' THEN Cantidad ELSE 0 END)) AS bult, sum((CASE WHEN EsUnid = '1' THEN Cantidad ELSE 0 END)) AS paq FROM saitemfac INNER JOIN saprod ON saitemfac.coditem = saprod.codprod INNER JOIN
				SAFACT ON SAITEMFAC.NumeroD = SAFACT.NumeroD WHERE SAFACT.CodSucu='00000' and
				DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN @fechai AND @fechaf AND saprod.marca LIKE '$marca' AND
				SAITEMFAC.codvend = '$ruta' AND saitemfac.TipoFac in ('F','C','A') AND SAFACT.TipoFac in ('F','C','A') AND SAFACT.NumeroD NOT IN
				(SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('F','C','A') AND x.NumeroR IS NOT NULL AND
				CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac = 'B') AS BIGINT)) GROUP BY saprod.Marca");
				
				foreach ($itemxmarcas as $row84){
					$Marca = $row84["Marca"];
					$bult = $row84["bult"];
					$paq = $row84["paq"];
				}

				$tbultos += $bult;
				$tpaquetes +=$paq;
				
				?>
			<tr>
				<td style="text-align: center;">
					<?php echo $Marca; ?>
				</td>
				<td style="text-align: center;">
					<?php echo number_format($bult,2); ?>
				</td>
				<td style="text-align: center;">
					<?php echo number_format($paq,2); ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<td height="20" colspan="8">
				<hr>
			</td>
		</tr>
		<tr>
			<td height="15" colspan="1" style="text-align: right;"><strong>Total: </strong></td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tbultos; ?>
			</td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tpaquetes; ?>
			</td>
		</tr>
		<tr>
			<td height="15" colspan="6" style="text-align: center;"></td>
		</tr>
	</tfoot>
</table>
<!-- tabla para resumen por item -->
<table width="978" class="Estilo2" height="" border="0" colspan="16">
	<!-- CABECERA DE TABLA     -->
	<thead>
		<tr>
			<td height="21" colspan="75" style="text-align: center;"><strong>
					<h1>Gestión por Producto</h1>
				</strong></td>
		</tr>
		<tr class="ui-widget-header">
			<th class="ui-widget-header">
				<div align="center">SKU </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Descripcion </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Clientes Activos </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Paquetes </div>
			</th>
			<th class="ui-widget-header">
				<div align="center">Unidades </div>
			</th>
		</tr>
	</thead>
	<!-- FIN DE CABECERA DE TABLA  -->
	<tbody>
		<?php
		$tbultos = $tpaquetes=0;
		foreach ($clientes_detalles as $row85) {
			$coditem = $row85["CodItem"];

			$cactivos = $modelo->consultaSQL("DECLARE @fechai DATE
												DECLARE @fechaf DATE
												set @fechai = GETDATE()
												set @fechaf = GETDATE()
												select  b.descrip from SAITEMFAC as a inner join safact as b on a.numerod = b.numerod where a.CodItem = '$coditem' and a.TipoFac in ('F','C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf and a.CodVend ='$ruta' and a.CodSucu='00000'");

			$ContadorActivos = 0;
			foreach ($cactivos as $row86) {
				$ContadorActivos++;
			}


			$bultos1 = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where coditem = '$coditem' and TipoFac in ('F','C','A')  and EsUnid = 0 and  DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and codvend = '$ruta' and CodSucu='00000'");

			foreach ($bultos1 as $row87) {
				$cantidadB1 = $row87["cantidad"];
			}


			$paquetes1 = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where coditem = '$coditem' and TipoFac in ('F','C','A')  and EsUnid = 1 and  DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and codvend = '$ruta' and CodSucu='00000'");

			foreach ($paquetes1 as $row88) {
				$cantidadP1 = $row88["cantidad"];
			}

			$tbultos += $cantidadB1;
			$tpaquetes +=$cantidadP1;

			?>
			<tr>
				<td style="text-align: left;">
					<?php echo $row85["CodItem"]; ?>
				</td>
				<td style="text-align: left;">
					<?php echo utf8_decode($row85["Descrip1"]); ?>
				</td>
				<td style="text-align: center;">
					<?php echo $ContadorActivos; ?>
				</td>
				<td style="text-align: center;">
					<?php echo number_format($cantidadB1,2); ?>
				</td>
				<td style="text-align: center;">
					<?php echo number_format($cantidadP1,2); ?>
				</td>
			</tr>
		</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<td height="20" colspan="8">
				<hr>
			</td>
		</tr>
		<tr>
			<td height="1" colspan="2" style="text-align: right;"><strong></strong></td>
			<td height="15" colspan="1" style="text-align: right;"><strong>Total: </strong></td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tbultos; ?>
			</td>
			<td height="1" colspan="1" style="text-align: center;">
				<?php echo $tpaquetes; ?>
			</td>
		</tr>
		<tr>
			<td height="15" colspan="6" style="text-align: center;"></td>
		</tr>
	</tfoot>
</table>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_inicio();">Volver</button>
	</p>
<div align="center">
	<a href="gestion_ver_pdf.php?&edv=<?php echo $ruta; ?>" data-icon="arrow-d" target="_blank">Exportar PDF</a>

</div>