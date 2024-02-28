<?php 
require("conexion.php");
session_start();
set_time_limit(0);
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
	$(function() {
		$("#fechaini").datepicker();
		$("#format").change(function() { $('fechaini').datepicker('option', {dateFormat: 'dd/mm/yy'}); });
	});
	$(function() {
		$("#fechafin").datepicker();
		$("#format").change(function() { $('fechafin').datepicker('option', {dateFormat: 'dd/mm/yy'}); });
	});
</script>
<script type="text/javascript">	
function volver_pedidos(){
location.href = "index.php?page=pedidos&mod=1";
}
function ped_x_fact(){
var codvend = document.getElementById('codvend').value;
var fechai = document.getElementById('fechaini').value;
var fechaf = document.getElementById('fechafin').value;
if (fechai != "" && fechaf != ""){
location.href="index.php?page=pedidos_facturados_relacion&mod=1&codvend="+codvend+"&fechai="+fechai+"&fechaf="+fechaf;
}else{
alert("DEBE COLOCAR RANGO DE FECHAS");
}
}
</script>
<h2>Relacion EDV</h2>
<p><strong>C&oacute;digo
	<?php if ($_SESSION['open'] == "99"){ ?>
    <input name="codvend" id="codvend" type="text" data-icon="search" />
	<?php }else{ ?>
		<?php if ($_SESSION['open'] == "01"){ ?>
				<select name="codvend" id="codvend" onChange="lista_prod()">
  					<option value="-">Vendedores (RUTA)</option>
					  <?php
					   		$consulta = mssql_query("select * from SAVEND WHERE Activo = 1 order by descrip");
						for($i=0;$i<mssql_num_rows($consulta);$i++){ ?>
								<option value="<?php echo mssql_result($consulta,$i,"CodVend"); ?>"><?php echo utf8_encode(mssql_result($consulta,$i,"CodVend")); ?></option>
						<?php } ?>
				</select>
			<?php }else{ ?>
					<input name="codvend" value="<?php echo $_SESSION['open']; ?>" disabled="disabled" id="codvend" type="text" data-icon="search" />
			<?php } ?>
	<?php } ?>
	</strong></p>
<p>Desde	
  <input name="fechaini" placeholder="dia/mes/anno" id="fechaini" type="text">
Hasta
<input name="fechafin"  placeholder="dia/mes/anno" id="fechafin" type="text">	
<a href="javascript:;" onClick="ped_x_fact();" data-role="button" data-icon="search">Buscar</a></p>

<div align="center"><p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_pedidos()">Volver</button></p></div>
