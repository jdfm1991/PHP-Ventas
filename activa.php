<?php 
require("conexion.php");
session_start();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function buscar_vend(){
var codvend = document.getElementById('codvend').value;
var fechai = document.getElementById('fechai').value;
var fechaf = document.getElementById('fechaf').value;
var aux = document.getElementById('activados').value;
if (fechai != "" && fechaf != ""){
location.href="index.php?page=activa_ver&mod=1&codvend="+codvend+"&fechai="+fechai+"&fechaf="+fechaf+"&aux="+aux;
}else{
alert("DEBE COLOCAR EL RANGO DE FECHAS");
}
}
</script> 
<script type="text/javascript">
function volver(){
location.href = "index.php";
}
</script>
<div data-role="header" data-theme="b">
    <h1>EDV (Activaci&oacute;n de Clientes)</h1>
</div>
<p><strong>C&oacute;digo

<?php if ($_SESSION['open'] == "99" or $_SESSION['open'] == "OT" or $_SESSION['open'] == "UTS" or $_SESSION['open'] == "MAYOR"){ ?>
    <input name="codvend" placeholder="Codigo del Vendedor" id="codvend" type="text" data-icon="search" /></strong></p>
	<?php }else{ ?>
	<input name="codvend"  placeholder="Codigo del Vendedor" value="<?php echo $_SESSION['open']; ?>" disabled="disabled"  id="codvend" type="text" data-icon="search" /></strong></p>
	<?php } ?>
	
Coloque un Rango de Fechas. Ejemplo: 01/12/2013 a 31/12/2013 </br> 
<p><strong>Desde
    <input name="fechai" id="fechai" value="01/<?php echo date("m/Y"); ?>" placeholder="dia/mes/anno"  type="text" data-icon="search" /></strong></p>
	<p><strong>Hasta
    <input name="fechaf" value="<?php echo date("d/m/Y"); ?>" id="fechaf" placeholder="dia/mes/anno"  type="text" data-icon="search" /></strong></p>	
	<select name="activados" id="activados">
	<option value="0">NO ACTIVADOS</option>
	<option value="1">ACTIVADOS</option>
	</select>
<a href="javascript:;" onClick="buscar_vend();" data-role="button" data-icon="search">Buscar</a>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
