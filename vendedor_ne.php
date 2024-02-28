<?php 
require("conexion.php");
session_start();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function buscar_vend(){
var descrip = document.getElementById('codvend').value;
if (descrip != ""){
location.href="index.php?page=vendedor_ne_ver&mod=1&descrip="+descrip;
}else{
alert("DEBE COLOCAR UN CODIGO DE CLIENTE");
}
}
</script> 
<script type="text/javascript">
function volver(){
location.href = "index.php";
}
</script>
<div data-role="header" data-theme="b">
    <h1>EDV</h1>
</div>
<p><strong>C&oacute;digo
	<?php if ($_SESSION['open'] == "99" or $_SESSION['open'] == "OT" or $_SESSION['open'] == "UTS" or $_SESSION['open'] == "MAYOR"){ ?>
    <input name="codvend" id="codvend" type="text" data-icon="search" />
	<?php }else{ ?>
	<input name="codvend" value="<?php echo $_SESSION['open']; ?>" disabled="disabled" id="codvend" type="text" data-icon="search" />
	<?php } ?>
	</strong></p>
<a href="javascript:;" onClick="buscar_vend();" data-role="button" data-icon="search">Buscar</a>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>

