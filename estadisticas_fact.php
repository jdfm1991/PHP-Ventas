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
function volver_esta(){
location.href = "index.php";
}
function esta_edv(){
var codvend = document.getElementById('codvend').value;
var fechai = document.getElementById('fechaini').value;
var fechaf = document.getElementById('fechafin').value;
if (fechai != "" && fechaf != "" && codvend != ""){
location.href="index.php?page=estadisticas_det_fact&mod=1&codvend="+codvend+"&fechai="+fechai+"&fechaf="+fechaf;
}else{
alert("DEBE COLOCAR UN RANGO DE FECHAS Y LA RUTA");
}
}
</script>
<h2>Estad&iacute;sticas por EDV </h2>
<p><strong>Ruta<?php if ($_SESSION['open'] == "99"){ ?>
    <input name="codvend" id="codvend" type="text" data-icon="search" />
	<?php }else{ ?>
	<input name="codvend" value="<?php echo $_SESSION['open']; ?>" disabled="disabled" id="codvend" type="text" data-icon="search" />
	<?php } ?>
	</strong></p>
Desde	
<input name="fechaini" placeholder="dia/mes/anno"  id="fechaini" type="text">
Hasta
<input name="fechafin"  placeholder="dia/mes/anno" id="fechafin" type="text">	
<a href="javascript:;" onClick="esta_edv();" data-role="button" data-icon="search">Buscar</a>
<p align="center"><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_esta()">Volver</button></p>