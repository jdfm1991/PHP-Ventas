<?php 
	require("conexion.php");
	session_start();
?>

<script type="text/javascript">
	function volver_inicio(){
		location.href = "index.php";
	}
</script>
<table  width="auto" data-role="header" data-theme="b"  border="0">
	<tr>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=0'; }else{ echo 'geo.php?opc=0'; } ?>" target="_blank">Todos</a></td>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=1'; }else{ echo 'geo.php?opc=1'; } ?>" target="_blank">Lunes</a></td>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=2'; }else{ echo 'geo.php?opc=2'; } ?>" target="_blank">Martes</a></td>
	</tr>
	<tr>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=3'; }else{ echo 'geo.php?opc=3'; } ?>" target="_blank">Miercoles</a></td>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=4'; }else{ echo 'geo.php?opc=4'; } ?>" target="_blank">Jueves</a></td>
		<td><a href="<?php if($_SESSION['open'] == 99){ echo 'geo2.php?opc=5'; }else{ echo 'geo.php?opc=5'; } ?>" target="_blank">Viernes</a></td>
	</tr>
</table>


<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_inicio();">Volver</button></p>