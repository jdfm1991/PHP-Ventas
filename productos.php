<?php 
require("conexion.php");
session_start();
$consulta = mssql_query("SELECT distinct(marca) from saprod  order  by marca asc");
$num = mssql_num_rows($consulta);
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function buscar_marca(){
var marca = document.getElementById('marca').value;
var tipo = document.getElementById('tipo').value;
var check = 0;
if (document.getElementById('exist_cero').checked == true){ 
check = 1;
}


	if (marca != "-" && tipo != "-"){
		location.href="index.php?page=productos_ver&mod=1&marca="+marca+"&tipo="+tipo+"&check="+check;
	}else{
		alert("DEBE ELEGIR UNA MARCA Y EL TIPO DE PRECIO QUE DESEA VER!!!");
	}
}
function volver(){
location.href = "index.php";
}
</script> 
<div data-role="header" data-theme="b">
    <h1> Ver Productos Marca (Instancia) </h1>
</div>
</br>


<select name="marca" id="marca">
  <option value="-">Elija una Marca</option>
  <?php for($i=0;$i<$num;$i++){ ?>
  <option value="<?php echo mssql_result($consulta,$i,"marca"); ?>"><?php echo utf8_encode(mssql_result($consulta,$i,"marca")); ?></option>
  <?php } ?>
  <option value="-"></option>
  <option value="-"></option>
  <option value="-"></option>
  </select>
  <select name="tipo" id="tipo">
  <option value="-">Elija un Tipo de Precio</option>
  <option value="2">MAYORISTA</option>
  <option value="1">DETAL</option>
  </select>
  
 <label>
        <input type="checkbox" checked="checked" id="exist_cero" name="checkbox-0">Todas las Existencias
    </label>

<a href="javascript:;" onClick="buscar_marca();" data-role="button" data-icon="search">Buscar</a>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>