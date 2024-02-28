<?php 
require("conexion.php");
set_time_limit(0);
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
function normalize_date($date){ //VENESUR
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			 return "$var[2]-$var[1]-$var[0]";
		 }
	}
$fechai = $_GET['fechai']; $fechai2 = str_replace('/','-',$fechai); $fechai2 = date('Y-m-d', strtotime($fechai2));
$fechaf = $_GET['fechaf']; $fechaf2 = str_replace('/','-',$fechaf); $fechaf2 = date('Y-m-d', strtotime($fechaf2));
$convend = $_GET['codvend'];
$aux = $_GET['aux'];
/*$fechai2 = normalize_date($fechai2);
$fechaf2 = normalize_date($fechaf2);*/
?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=activa&mod=1";
}
</script>
<?php
$cant_clie = mssql_query("select codclie, descrip, id3, direc1 from saclie where codvend = '$convend' and activo = 1 ");
$num_clie = mssql_num_rows($cant_clie); 
$cant_clie_dist = mssql_query("select distinct safact.codclie FROM safact inner join saclie on safact.codclie = saclie.codclie  where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$convend' and tipofac in ('A','C')");
$num_cant_clie_dis = mssql_num_rows($cant_clie_dist); 
?>
<?php if ($aux == 0){ ?>
<p data-role="header" data-theme="b"><strong>Clientes NO ACTIVADOS , EDV: <?php echo $convend; ?>, Desde: <?php echo $_GET['fechai']; ?> Hasta:  <?php echo $_GET['fechaf']; ?></strong></p>	
<div style="width:auto; overflow:scroll;">
<table width="622" height="78" border="0" data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
  <thead class="ui-btn-active">
	<tr>
	<th data-priority="2"><div align="center"><strong>CodClie</strong></div></th>
    <th data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
	<th data-priority="3"><div align="center"><strong>Rif</strong></div></th>
    <th data-priority="3"><div align="center"><strong></strong>Direcci&oacute;n</div></th>
  </tr>
  </thead>
   <tbody>
  <?php
  $cont=0;  
		for($i=0;$i<$num_clie;$i++){
		?>
  <?php 
			$codecliente = mssql_result($cant_clie,$i,"codclie");
			/*$busca_no_activado = mssql_query("select saclie.descrip as cliente FROM safact inner join saclie on safact.codclie = saclie.codclie  where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$convend' and safact.codclie = '$codecliente' and tipofac = 'A'");*/
			
			$busca_no_activado = mssql_query("select distinct saclie.codclie FROM saclie inner join safact on saclie.codclie = safact.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and tipofac in ('A','C') and saclie.codvend = '$convend' and safact.codclie = '$codecliente'");
			
			if (mssql_num_rows($busca_no_activado) == 0){
				$cont++;
				?>
				<tr <?php if (($cont % 2) != 0){ ?>
   				bgcolor="#CCCCCC"
  				<?php } ?>>
    			<td><div align="center"><?php echo mssql_result($cant_clie,$i,"codclie"); ?></div></td>
    			<td><div align="center"><?php echo utf8_encode(mssql_result($cant_clie,$i,"descrip")); ?></div></td>
    			<td><div align="center"><?php echo mssql_result($cant_clie,$i,"id3"); ?></div></td>
    			<td><div align="center"><?php echo utf8_encode(mssql_result($cant_clie,$i,"direc1")); ?></div></td>
    			</tr>
				
				<?php
			}
		}?>
		<tr >
				  <td colspan="4">Total de NO ACTIVADOS: <?php echo $cont; ?>, de <?php echo $num_clie; ?> Clientes</td>
  		</tr>
  </tbody>
</table>	
<?php }else{ ?>
<p data-role="header" data-theme="b"><strong>Clientes ACTIVADOS , EDV: <?php echo $convend; ?></strong></p>	
<table width="622" height="78" border="0" data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
  <thead class="ui-btn-active">
	<tr>
	<th data-priority="2"><div align="center"><strong>CodClie</strong></div></th>
    <th data-priority="2"><div align="center"><strong>Descrip</strong></div></th>
	<th data-priority="3"><div align="center"><strong>Rif</strong></div></th>
    <th data-priority="3"><div align="center"><strong></strong>Direcci&oacute;n</div></th>
  </tr>
  </thead>
   <tbody>
  <?php
  $cont=0;  
		for($i=0;$i<$num_clie;$i++){
		?>
  <?php 
			$codecliente = mssql_result($cant_clie,$i,"codclie");
			$busca_no_activado = mssql_query("select distinct saclie.codclie FROM saclie inner join safact on saclie.codclie = safact.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and tipofac in ('A','C') and saclie.codvend = '$convend' and safact.codclie = '$codecliente'");
			
			
			
			if (mssql_num_rows($busca_no_activado) != 0){
				$cont++;
				?>
				<tr <?php if (($cont % 2) != 0){ ?>
   				bgcolor="#CCCCCC"
  				<?php } ?>>
    			<td><div align="center"><?php echo mssql_result($cant_clie,$i,"codclie"); ?></div></td>
    			<td><div align="center"><?php echo utf8_encode(mssql_result($cant_clie,$i,"descrip")); ?></div></td>
    			<td><div align="center"><?php echo mssql_result($cant_clie,$i,"id3"); ?></div></td>
    			<td><div align="center"><?php echo utf8_encode(mssql_result($cant_clie,$i,"direc1")); ?></div></td>
    			</tr>
				
				<?php
			}
		}?>
		<tr >
				  <td colspan="4">Total de ACTIVADOS: <?php echo $cont; ?>, de <?php echo $num_clie; ?> Clientes</td>
  		</tr>
  </tbody>
</table>			
</div>
<?php } ?>	
</br>
<a href="activa_excel.php?&fechai=<?php echo $_GET['fechai']; ?>&fechaf=<?php echo $_GET['fechaf']; ?>&codvend=<?php echo $_GET['codvend']; ?>&aux=<?php echo $_GET['aux']; ?>" target="_blank" > Imprimir en Excel</a>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>	
