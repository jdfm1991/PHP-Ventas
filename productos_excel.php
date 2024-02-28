<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Lista_De_Precios_AJ".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
session_start();
set_time_limit(0);
$marca = $_GET['marca'];
$tipo = $_GET['tipo'];
$check = $_GET['check'];
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
// $depo = "01";
// if($_SESSION['canal'] == 'DETAL'){
// $depo = "20"; //nestle
// }
// if($_SESSION['canal'] == 'OT'){
// $depo = "30"; //nestle
// }
// $consulta = mssql_query("select descrip from sainsta where codinst = '$marca' order by descrip");
// $consulta_padre = mssql_query("select descrip from sainsta where InsPadre = '$marca' order by descrip");
// if ($check == 1){
if ($check == 1){
  $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, SAEXIS.Existen, SAEXIS.ExUnidad, SAPROD_02.Precio1_B, SAPROD_02.Precio1_P, SAPROD_02.Precio2_B, SAPROD_02.Precio2_P from saexis inner join saprod on saexis.codprod = saprod.codprod inner join SAPROD_02 on saprod.CodProd = SAPROD_02.CodProd where (saexis.codubic = '01') and (saexis.existen > 0 or saexis.exunidad > 0) and saprod.marca like '$marca' and saprod.activo <> '0' order by saprod.descrip");

}else{

  $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, SAEXIS.Existen, SAEXIS.ExUnidad, SAPROD_02.Precio1_B, SAPROD_02.Precio1_P, SAPROD_02.Precio2_B, SAPROD_02.Precio2_P from saexis inner join saprod on saexis.codprod = saprod.codprod inner join SAPROD_02 on saprod.CodProd = SAPROD_02.CodProd where (saexis.codubic = '01') and saprod.marca like '$marca' and saprod.activo <> '0' and (saexis.existen = 0 and saexis.ExUnidad = 0) order by saprod.descrip");

}

$num = mssql_num_rows($productos);
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=productos&mod=1";
}
</script>
<h3>Productos <?php 
if ($tipo == 1){
echo " (DETAL)";
}else{
echo " (MAYORISTA)";
}
$query_iva = mssql_query("select MtoTax from sataxes where codtaxs = 'IVA'");
$iva = 1+(mssql_result($query_iva, 0, 'mtotax')/100);
echo " ".date('d-m-Y h:i a',time() - 3600*date('I'));
 ?></h3>
<table width="727" class="ui-responsive table-stroke" id="table-column-toggle" data-role="table" data-mode="columntoggle">
  <thead class="ui-btn-active">
    <tr bgcolor="#0099FF">
      <th width="70" data-priority="2"><div align="center"><strong>CodProd</strong></div></th>
      <th width="248" data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
      <th width="62" data-priority="3"><div align="center">
        <p><strong> Bulto</strong>s</p>
      </div></th>
      <th width="83" data-priority="3"><div align="center"><strong>Precio Bulto </strong></div></th>
      <th width="34" data-priority="5"><div align="center"><strong> Paq </strong></div></th>
      <th width="71" data-priority="6"><div align="center"><strong>Precio Paq </strong></div></th>
    </tr>
  </thead>
  <tbody>
  <?php for($i=0;$i<$num;$i++){ ?>
    <tr <?php if (($i % 2) != 0){ ?>
   bgcolor="#CCCCCC"
  <?php } ?>>
      <th><div align="center"><?php echo mssql_result($productos,$i,"codprod"); ?></div></th>
      <th><div align="left"><?php echo utf8_encode(mssql_result($productos,$i,"descrip")); ?></div>
          <div align="left"></div></th>
      <?php if ($_SESSION['open']){ ?>
      <td><div align="center"><?php echo round(mssql_result($productos,$i,"existen")); ?></div></td>
      <?php }else{ ?>
      <td><div align="center"><?php echo round(mssql_result($productos,$i,"existen")); ?></div></td>
      <?php } ?>
      <?php if ($tipo == 1){ ?>
      <td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio1_B"), 2, ",", "."); ?></div></td>
      <?php }else{ ?>
      <td><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio2_B"), 2, ",", "."); ?></div></td>
      <?php } ?>
      <?php if ($_SESSION['open']){ ?>
      <td width="20"><div align="center"><?php echo round(mssql_result($productos,$i,"exunidad")); ?></div></td>
      <?php }else{ ?>
      <td width="27"><div align="center"><?php echo round(mssql_result($productos,$i,"exunidad")); ?></div></td>
      <?php } ?>
      <?php if ($tipo == 1){ ?>
      <td width="27"><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio1_P"), 2, ",", "."); ?></div></td>
      <?php }else{ ?>
      <td width="41"><div align="center"><?php echo number_format(mssql_result($productos,$i,"precio2_P"), 2, ",", "."); ?></div></td>
      <?php } ?>
    </tr>
    <?php } ?>
  </tbody>
</table>
Total de Productos <?php echo $num; ?>
</br>