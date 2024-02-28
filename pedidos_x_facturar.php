<?php
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
//session_start();
set_time_limit(0);
function normalize_date($date)
{ //VENESUR
	if (!empty($date)) {
		$var = explode('/', str_replace('-', '/', $date));
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

function decimal($val)
{
	return number_format($val, 2, ",", ".");
}
if ($_SESSION['open'] == "") {
	echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
	function volver_ped() {
		location.href = "index.php?page=pedidos_busca&mod=1";
	}
</script>
<h3>Pedidos Pendientes por Facturar, Ruta:
	<?php echo $convend; ?> al
	<?php echo $_GET['fechai']; ?> Hasta
	<?php echo $_GET['fechaf']; ?>
</h3>
<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
	<?php
	if ($convend) {
		$presupuesto = $modelo->consultaSQL("select distinct(numerod) from saux where DATEADD(dd, 0, DATEDIFF(dd, 0, fecha)) between '$fechai2' and '$fechaf2' and estatus = '1' and codvend = '$convend' order by numerod desc");
	} else {
		$presupuesto = $modelo->consultaSQL("select distinct(numerod) from saux where DATEADD(dd, 0, DATEDIFF(dd, 0, fecha)) between '$fechai2' and '$fechaf2' and estatus = '1' order by numerod desc");
	}
	$ContadorPresupuesto = 0;
	foreach ($presupuesto as $row) {
		$ContadorPresupuesto++;
	}
	$cuenta = 0;
	$ContadorFact = 0;
	$ContadorItemfact = 0;
	if (($ContadorPresupuesto) != 0) {

		foreach ($presupuesto as $row) {
			$numerod = $row["numerod"];
			$safact = $modelo->consultaSQL("select numerod, codvend, descrip, fechae from safact where numerod = '$numerod' and tipofac = 'F' and CodSucu='00000'");

			$saitemfact = $modelo->consultaSQL("select numerod from saitemfac where Otipo = 'F' and Onumero = '$numerod' and tipofac = 'A' and CodSucu='00000'");
			
			foreach ($safact as $row1) {
				$ContadorFact++;
				$descrip = $row1["descrip"];
				$fechae = $row1["fechae"];
				$codvend = $row1["codvend"];
			}
			
			foreach ($saitemfact as $row2) {
				$ContadorItemfact++;
			}
			
			if ($ContadorItemfact == 0 and $ContadorFact != 0) {
				$cuenta++;
				?>
				<div data-role="collapsible">
					<h3>NRO
						<?php echo $numerod; ?>,
						<?php echo utf8_encode($descrip); ?> FECHA:
						<?php echo date("d/m/Y h:i:s A", strtotime($fechae)); ?>, EDV:
						<?php echo $codvend; ?>
					</h3>
					<?php
					$saux_clientes = $modelo->consultaSQL("select descriprod,unidad,pedido,id_pedido,precio,esexcento from saux where numerod = '$numerod' and estatus = 1 order by id_pedido desc");
					?>
					<p>
					<table>
						<thead class="ui-btn-active">
							<tr>
								<th data-priority="2">
									<div align="center"><strong>Descrip</strong></div>
								</th>
								<th data-priority="2">
									<div align="center"><strong>Pedido</strong></div>
								</th>
								<th data-priority="2">
									<div align="center"><strong>Total</strong></div>
								</th>
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
								foreach ($saux_clientes as $row3) { ?>
									<td>
										<div align="left">
											<?php echo utf8_encode($row3["descriprod"]); ?>
										</div>
									</td>
									<td>
										<div align="center">
											<?php
											echo round($row3["pedido"]) . " ";
											if ($row3["unidad"] == 0) {
												echo "Paq";
												$bult = $bult + round($row3["pedido"]);
											} else {
												echo "Uni";
												$paq = $paq + round($row3["pedido"]);
											} ?>
										</div>
									</td>
									<td>
										<div align="center">
											<?php
											echo decimal($row3["pedido"] * $row3["precio"]);
											$base = $base + $row3["pedido"] * $row3["precio"];
											if ($row3["esexcento"] == 0) {
												$total = $total + (($row3["pedido"] * $row3["precio"]) * 1.16);
												$iva = $iva + (($row3["pedido"] * $row3["precio"]) * 0.16);
											} else {
												$total = $total + ($row3["pedido"] * $row3["precio"]);
											}
											?>
										</div>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td>Total Paquete:
									<?php echo $bult; ?>
								</td>
								<td>
									<div align="right">Base Imp</div>
								</td>
								<td>
									<div align="center"><strong>
											<?php echo decimal($base); ?>
										</strong></div>
								</td>
							</tr>
							<tr>
								<td>Total Unidad:
									<?php echo $paq; ?>
								</td>
								<td>
									<div align="right">Iva</div>
								</td>
								<td>
									<div align="center"><strong>
											<?php echo decimal($iva); ?>
										</strong></div>
								</td>
							</tr>
							<tr>
								<td>-</td>
								<td>
									<div align="right">Total</div>
								</td>
								<td>
									<div align="center"><strong>
											<?php echo decimal($total); ?>
										</strong></div>
								</td>

							</tr>
							<tr>
								<td>
									<a href="detalle.php?&numd=<?php echo $numerod; ?>&tipo=<?php echo "F"; ?>"
										data-rel="dialog">Visualizar Pedido</a>
								</td>

							</tr>
						</tbody>
					</table>
					</p>
				</div>

			<?php

			}

		}
		echo "TOTAL DE PEDIDOS POR FACTURAR: " . $cuenta;
		?>

	</div>

<?php

	} else {
		echo "NO HAY PEDIDOS CARGADOS EN SISTEMA";
	}
	?>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_ped()">Volver</button></p>