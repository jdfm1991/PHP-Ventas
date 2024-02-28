<?php 
require("conexion.php");
session_start();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function buscar_clie(){
var descrip = document.getElementById('codclie').value;
if (descrip != ""){
location.href="index.php?page=clientes&mod=1&descrip="+descrip;
}else{
alert("DEBE COLOCAR UNA DESCRIPCION DEL CLIENTE");
}
}
</script> 
<script type="text/javascript">
function volver(){
location.href = "index.php";
}
</script>
<div data-role="header" data-theme="b">
    <h1>Buscar Cliente</h1>
</div>
<p><strong>Descripci&oacute;n 
    <input name="codclie" id="codclie" type="text" data-icon="search" /></strong></p>
<a href="javascript:;" onclick="buscar_clie();" data-role="button" data-icon="search">Buscar</a>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
