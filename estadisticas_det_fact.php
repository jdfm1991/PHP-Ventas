<?php 
require("conexion.php");
set_time_limit(0);
function normalize_date($date){ //VENESUR
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			 return "$var[2]-$var[1]-$var[0]";
		 }
	}
function decimal($val){
return number_format($val, 2, ",", ".");
}
$fechai = $_GET['fechai']; $fechai2 = str_replace('/','-',$fechai); $fechai2 = date('Y-m-d', strtotime($fechai2));
$fechaf = $_GET['fechaf']; $fechaf2 = str_replace('/','-',$fechaf); $fechaf2 = date('Y-m-d', strtotime($fechaf2));
$codvend = $_GET['codvend'];
/*$fechai2 = normalize_date($fechai2);
$fechaf2 = normalize_date($fechaf2);*/

$resumen = mssql_query("SELECT 
(select codvend from SAVEND where CodVend = '$codvend') as CodRuta,
(select Descrip from SAVEND where CodVend = '$codvend') as EDV,
(SUM(case when TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
SUM(case when TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) - (
SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) as BultVent,
(SUM(case when TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
SUM(case when TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as UnidVent,
(SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) as BultDev,
(SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as UnidDev,
(select count(distinct(SAFACT.CodClie)) from SAFACT inner join SACLIE on SAFACT.CodClie = SACLIE.CodClie where ACTIVO = 1 AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai2' and '$fechaf2' 
and SAFACT.TipoFac = 'A' and SAFACT.CodVend = '$codvend') as Activados,
(select count(distinct(Saclie.CodClie)) from SACLIE inner join SACLIE_01 on SACLIE.CodClie = SACLIE_01.CodClie where (SACLIE.CodVend = '$codvend' or Ruta_Alternativa = '$codvend') AND SACLIE.ACTIVO = 1) as Maestro,
(select count(distinct(Saclie.CodClie)) from SACLIE inner join SACLIE_01 on SACLIE.CodClie = SACLIE_01.CodClie where (SACLIE.CodVend = '$codvend' or Ruta_Alternativa = '$codvend') AND SACLIE.ACTIVO = 1) -
(select count(distinct(SAFACT.CodClie)) from SAFACT inner join SACLIE on SAFACT.CodClie = SACLIE.CodClie where ACTIVO = 1 AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai2' and '$fechaf2' 
and SAFACT.TipoFac = 'A' and SAFACT.CodVend = '$codvend') as Pendientes,
(SUM(case when TipoFac = 'A' and EsUnid = 1 then (saitemfac.Cantidad/saprod.CantEmpaq) * SAPROD.Tara else 0 end) +
SUM(case when TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad * SAPROD.Tara else 0 end)) - (
SUM(case when TipoFac = 'B' and EsUnid = 1 then (saitemfac.Cantidad/saprod.CantEmpaq) * SAPROD.Tara else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad * SAPROD.Tara else 0 end)) as KilVent,
SUM(case when TipoFac = 'B' and EsUnid = 1 then (saitemfac.Cantidad/saprod.CantEmpaq) * SAPROD.Tara else 0 end) +
SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad * SAPROD.Tara else 0 end) as KilDev,
(select Requerido_Bult_Und from SAVEND_02 where CodVend = '$codvend') as Objetivo,
(select Clase from SAVEND where CodVend = '$codvend') as Tipo,
(select sum(MtoTotal) from safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend' and tipofac = 'A') -
(select sum(MtoTotal) from safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend' and tipofac = 'B')
as BsVent,
(select sum(MtoTotal) from safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend' and tipofac = 'B')
as BsDev,


(SUM(case when TipoFac = 'F' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
SUM(case when TipoFac = 'F' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) AS BultPres,

(SUM(case when TipoFac = 'F' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
SUM(case when TipoFac = 'F' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) AS UnidPres,



(SUM(case when TipoFac = 'F' and EsUnid = 1 then (saitemfac.Cantidad/saprod.CantEmpaq) * SAPROD.Tara else 0 end) +
SUM(case when TipoFac = 'F' and EsUnid = 0 then saitemfac.Cantidad * SAPROD.Tara else 0 end)) AS KilPres,
(select sum(MtoTotal) from safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend' and tipofac = 'F')
as BsPres,
(select count(distinct(Onumero)) from SAITEMFAC where DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai2' and
 '$fechaf2' and CodVend = '$codvend' and Onumero is not null and Otipo = 'F' and TipoFac = 'A')  as PediFact,
 
(select count(distinct(numerod)) from SAFACT 
WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, Fechae)) between '$fechai2' and '$fechaf2' AND CodVend = '$codvend' and TipoFac = 'A') as facturas,

(select count(distinct(numerod)) from SAFACT 
WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, Fechae)) between '$fechai2' and '$fechaf2' AND CodVend = '$codvend' and TipoFac = 'B') as devoluciones
 
 
FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
where DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend'");

$resumen_cxc = mssql_query("

SELECT sum(saldo) as saldo FROM saacxc  where DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) between '$fechai2' and '$fechaf2' and CodVend = '$codvend' and TipoCxc = '10' ")

?>
<script type="text/javascript">
function volver(){
location.href = "index.php?page=estadisticas_fact&mod=1";
}
</script>
<h3>Estad&iacute;sticas Facturas, Ruta: <?php echo $codvend; ?> al <?php echo $_GET['fechai']; ?> Hasta <?php echo $_GET['fechaf']; ?></h3>
<div style="width:auto; overflow:scroll;">
<table width="616" border="0" data-mode="columntoggle" class="ui-responsive table-stroke">
   <thead class="ui-btn-active">
 <tr>
    <th data-priority="2">EDV</th>
    <th data-priority="2"><?php echo mssql_result($resumen,0,"codruta");  ?> <?php echo mssql_result($resumen,0,"edv")." (".mssql_result($resumen,0,"tipo").")";  ?></th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="258">Maestro de Clientes</td>
    <td width="348"><?php echo mssql_result($resumen,0,"maestro");  ?></td>
  </tr>
  <tr  bgcolor="#CCCCCC">
    <td>Clientes Activados</td>
    <td><?php echo mssql_result($resumen,0,"activados");  ?></td>
  </tr >
  <tr>
    <td>Clientes Pendientes por Activar</td>
    <td><?php echo mssql_result($resumen,0,"pendientes");  ?></td>
  </tr>

  <tr>
    <td bgcolor="#CCCCCC">Ventas Brutas </td>
    <td bgcolor="#CCCCCC"><?php echo decimal(mssql_result($resumen,0,"bsvent"));  ?> Bs</td>
  </tr>
  <tr >
    <td>Devoluciones </td>
    <td><?php echo decimal(mssql_result($resumen,0,"bsdev"));  ?> Bs</td>
  </tr>
 <tr bgcolor="#CCCCCC"  >
    <td  >Ventas Netas</td>

    <?php 
      $totalneto =  mssql_result($resumen,0,"bsvent") - mssql_result($resumen,0,"bsdev");
      
     ?>
    <td ><?php echo decimal($totalneto);  ?> $</td>
  </tr>
  
  <tr >
    <td >Numero de Facturas </td>
    <td><?php echo mssql_result($resumen,0,"facturas");  ?></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td>Numero de Devoluciones </td>
    <td><?php echo mssql_result($resumen,0,"devoluciones");  ?></td>
  </tr>
   <tr >
    <td>CXC Pendientes </td>
    <td ><?php echo decimal(mssql_result($resumen_cxc,0,"saldo"));  ?> Bs</td>
  </tr>
  <tr class="ui-btn-active">
    <td><?php echo "-";  ?></td>
    <td><?php echo "-";  ?></td>
  </tr>
  </tbody>
</table>
</div>
<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
