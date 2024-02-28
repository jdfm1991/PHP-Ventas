<?php 
require("conexion.php");
session_start();
$descip = $_GET['descrip'];
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=menu&mod=1";
}
function ver_clie(code){
location.href = "index.php?page=clientes_det&mod=1&codclie="+code+"&descri=<?php echo $descip; ?>";
}
</script>
    <h3>Lista de Clientes</h3>
	<ul data-role="listview">
<?php 
$codvend = $_SESSION['open'];
if ($_SESSION['open'] == "99" or $_SESSION['open'] == "OT" or $_SESSION['open'] == "UTS" or $_SESSION['open'] == "MAYOR"){ 
	$clientes= mssql_query("select codclie, descrip from saclie where descrip like '%$descip%' order by descrip");
}else{
	$clientes= mssql_query("select codclie, descrip from saclie where descrip like '%$descip%' and codvend = '$codvend' order by descrip");
}
	  	if (mssql_num_rows($clientes) != 0){ 
			for($i=0;$i<mssql_num_rows($clientes);$i++){?>
			
   			<li><a onClick="ver_clie('<?php echo mssql_result($clientes,$i,"codclie"); ?>')"><?php echo utf8_encode(mssql_result($clientes,$i,"descrip")); ?></a></li>
			
			<?php 
			}
		?>
		</ul>
		<?php 	
		}else{
		echo "NO HAY REGISTROS";
		}
	  ?>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>