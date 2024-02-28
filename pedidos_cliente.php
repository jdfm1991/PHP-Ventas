<?php
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
session_start();
set_time_limit(0);
$codclie = $_POST['codclie'];
$estatus = 0;
$codvend = $_SESSION['open'];
function decimal($val)
{
	return number_format($val, 2, ",", ".");
}
$datos = $modelo->consultaSQL("select codprod, descriprod,unidad,pedido,id_pedido,precio,esexcento from saux where codclie = '$codclie' and estatus = 0 and codvend = '$codvend' order by linea");
$Contadorclie = 0;
foreach ($datos as $row) {
	$Contadorclie++;
}
if (($Contadorclie) != 0) {
	$num = ($Contadorclie);
	?>
	<style type="text/css">
		<!--
		.Estilo1 {
			font-size: 12px
		}
		-->
		input[type=number]
		{
		width:
		50px;
		}
	</style>
	<?php
	$datoslineas = $modelo->consultaSQL("select linea from SAUX where codclie = '$codclie' and estatus = 0 order by linea desc");
	$linea=0;
	foreach ($datoslineas as $row) {
		$linea++;
	}
	if ($linea < 19) {
		?>
		<strong>
			<p style="color: blue;">Solo puede seleccionar un máximo de 19 items por presupuesto, usted tiene por los momentos
				<?php echo $linea; ?> items
			</p>
		</strong>
		<?php
	} else {
		?>
		<strong>
			<p style="color: red;">Ya ha llegado a un máximo de 19 items por presupuesto, si desea más items, por favor generar
				otro presupuesto</p>
		</strong>
		<?php
	}
	?>
	<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead class="ui-btn-active">
			<tr>
				<th data-priority="2">
					<div align="center"><strong>Codigo</strong></div>
				</th>
				<th data-priority="2">
					<div align="center"><strong>Descrip</strong></div>
				</th>
				<th data-priority="2">
					<div align="center"><strong>Pedido</strong></div>
				</th>
				<th data-priority="2">
					<div align="center"><strong>Total</strong></div>
				</th>
				<th data-priority="2">
					<div align="center"><strong>Cancel</strong></div>
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
				$query_iva = $modelo->consultaSQL("select MtoTax from sataxes where codtaxs = 'IVA'");
				foreach ($query_iva as $row) {
					$ivas = 1 + $row["MtoTax"] / 100;
					$MtoTax = $row["MtoTax"];
				}
				foreach ($datos as $row) { ?>
					<td>
						<div align="left">
							<?php echo $row["codprod"]; ?>
						</div>
					</td>
					<td>
						<div align="left">
							<?php echo utf8_encode($row["descriprod"]); ?>
						</div>
					</td>
					<td>
						<div align="center">
							<?php
							echo round( $row["pedido"] ) . " ";
							if ($row["unidad"]  == 0) {
								echo "Paq";
								$bult = $bult + round($row["pedido"]);
							} else {
								echo "Uni";
								$paq = $paq + round($row["pedido"]);
							} ?>
						</div>
					</td>
					<td>
						<div align="center">
							<?php
							echo decimal($row["pedido"] * $row["precio"]);
							$base = $base + $row["pedido"] * $row["precio"];
							if ($row["esexcento"]  == 0) {
								$total = $total + (($row["pedido"] * $row["precio"]) * $ivas);
								$iva = $iva + (($row["pedido"] * $row["precio"]) * ($MtoTax / 100));
							} else {
								$total = $total + ($row["pedido"] * $row["precio"]);
							}
							?>
						</div>
					</td>
					<td>
						<div align="center"><a href="javascript:;"
								onClick="borra_item('<?php echo $row["id_pedido"]; ?>')"><img
									src="img/delete.gif" width="25" height="21" border="0"></a></div>
					</td>
				</tr>
				<?php
				}
				?>
			<tr>
				<td>Total Paq:
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
				<td>Total Uni:
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
				<td colspan="4">
					<div align="center"><input id="nota" name="nota" type="text" max="60"
							placeholder="CAMPO PARA OBSERVACION" size="60"></div>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center"><a href="javascript:;" class="ui-btn-down-b"
							onClick="finaliza_pedido('<?php echo $num; ?>','<?php echo $codclie; ?>','nota')">Finalizar
							Pedido</a></div>
				</td>
			</tr>
		</tbody>
	</table>
<?php
} else {
	echo "Este Cliente No Tiene Pedidos En Espera";
}
echo "<script language=Javascript>prehide();</script>";
?>