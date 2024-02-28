<script type="text/javascript">
  function volver(){
    location.href = "index.php";
  }
  function motivos_visita(){
    var cl =  document.getElementById('cliente').value;
    var ed =  document.getElementById('edv').value;
    var mo =  document.getElementById('motivo').value.split(";");
    document.getElementById("envio").action= "index.php?page=pedidos&mod=1";
    document.envio.submit()
  }
</script>
<?php
  if ($_POST['edv']) {
    $motivo = $_POST['motivo'];
    $cliente = $_POST['cliente'];
    $edv = $_POST['edv'];
    if (date('D')=='Mon') {
      $fecha = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-3, date('Y')));
    }else{
      $fecha = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
    }
    if ($motivo != 0) {
      $insertar = mssql_query("insert into visitas_edv (CodClie, edv, fecha, motivo) values ('$cliente', '$edv', '$fecha', $motivo)");
    }
    unset($_POST['edv']);
  }
  if (date('D')=='Mon') {
    $ayer = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-3, date('Y')));
    $dia_ayer = valida_dia(date('D', mktime(0, 0, 0, date('m'), date('d')-3, date('Y')))); if($dia_ayer == 'Miércoles'){ $dia_ayer = 'Miercoles'; }
  }else{
    $ayer = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
    $dia_ayer = valida_dia(date('D', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')))); if($dia_ayer == 'Miércoles'){ $dia_ayer = 'Miercoles'; }
  }
  $activados = mssql_query("select  SACLIE.CodClie as Codclie, Descrip as Cliente, ID3 as Rif, Direc1, Telef, Saldo as SaldoPendiente, SACLIE_01.DiasVisita
  from SACLIE inner join SACLIE_01 on SACLIE.CodClie = SACLIE_01.CodClie
  where (SACLIE.CodVend = '$codvend' or Ruta_Alternativa = '$codvend' or Ruta_Alternativa_2 = '$codvend') and (SACLIE_01.DiasVisita like '$dia_ayer') and ((SACLIE.codclie not in (select distinct safact.CodClie from safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$ayer' and '$ayer' and safact.CodVend = '$codvend')) and (SACLIE.codclie not in (select distinct visitas_edv.CodClie from visitas_edv where DATEADD(dd, 0, DATEDIFF(dd, 0, visitas_edv.Fecha)) between '$ayer' and '$ayer' and visitas_edv.edv = '$codvend'))) order by descrip");
  $num = mssql_num_rows($activados);
  $cont_aux = 0;
?><!-- as -->
<p data-role="header" data-theme="b"><strong>LISTA DE CLIENTES SIN MOTIVO DE VENTAS POR EL EDV: <?php echo $codvend; ?> EL DIA <?php echo date('d-m-Y', mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))); ?> </strong></p>
<table width="622" height="78" border="0" data-role="table" id="table-column-toggle"  class="ui-responsive table-stroke">
<!--   <table width="622" height="78" border="0" data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke"> -->
  <thead class="ui-btn-active">
  <tr>
    <th data-priority="2"><div align="center"><strong>DíaVisita</strong></div></th>
    <th data-priority="2"><div align="center"><strong>Cliente</strong></div></th>
    <th data-priority="3"><div align="center"><strong></strong>Direcci&oacute;n</div></th>
    <th data-priority="3"><div align="center"><strong></strong>Tel&eacute;fono</div></th>
    <th data-priority="3"><div align="center"><strong></strong>Saldo Pendiente</div></th>
    <th data-priority="3"><div align="center"><strong></strong>Motivo</div></th>
  </tr>
  </thead>
  <tbody>
  <?php for($i=0;$i<$num;$i++){

       $codclie = mssql_result($activados,$i,"Codclie");
    $cxc = mssql_query("SELECT sum(saldo) as saldo FROM AJ_D.DBO.SAACXC WHERE codclie = '$codclie'");
    $saldo = mssql_result($cxc,$j,"saldo");
  ?>
        <tr <?php if (($cont % 2) != 0){ ?> bgcolor="#CCCCCC" <?php } ?>>
              <td><div align="center"><?php echo ucwords(strtolower(mssql_result($activados,$i,"DiasVisita"))); ?></div></td>
              <td><div align="center"><?php echo utf8_encode(mssql_result($activados,$i,"cliente")); ?></div></td>
              <td><div align="center"><?php echo utf8_encode(mssql_result($activados,$i,"direc1")); ?></div></td>
              <td><div align="center"><?php echo utf8_encode(mssql_result($activados,$i,"telef")); ?></div></td>
               <td><div align="center"><?php echo decimal(mssql_result($cxc,$j,"saldo")); ?> $</div></td>
              <td><div align="center">
  <?php
        $cliente = mssql_result($activados,$i,"codclie");
        $fechai = date('Y-m-d', strtotime('last Monday'));
        if(date('D', mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))) == 'Sat'){
          $fechaf = date('Y-m-d');
        }else{
          $fechaf = date('Y-m-d', strtotime('next Saturday'));
        }
        $visitado = mssql_query("
            declare @fechai date
            declare @fechaf date
            set @fechai = '$fechai'
            set @fechaf = '$fechaf'
            select * from visitas_edv where CodClie = '$cliente' and edv = '$codvend' AND
            DATEADD(dd, 0, DATEDIFF(dd, 0, fecha)) BETWEEN @fechai AND @fechaf");
        if (mssql_num_rows($visitado)==0) {
  ?>
                <form action="" method="post" id="envio" name="envio">
                  <input type="hidden" name="cliente" value="<?php echo $cliente; ?>">
                  <input type="hidden" name="edv" value="<?php echo $codvend; ?>">
                  <select name="motivo" id="motivo" require>
                            <option value="0">Seleccione un motivo</option>
                <option value="1">Cliente cerrado</option>
                <option value="2">Cliente con inventario</option>
                <option value="3">Cliente a la espera de pedido anterior</option>
                <option value="4">Cliente no visitado</option>
                <option value="5">Cliente fuera de Ruta</option>
                <option value="6">Cliente con deuda y sin pago</option>
                <option value="7">Cliente compra a la competencia</option>
                <option value="8">Cliente considera altos los precios</option>
                <option value="9">Causa Administrativa</option>
                  </select>
                  <input type="submit" name="boton" value="Enviar" onClick="motivos_visita();">
                </form>
  <?php
        }else{
          echo motivo_cliente(mssql_result($visitado,0,"motivo"));
        }
  ?>
              </div></td>
          </tr>
  <?php $cont_aux++; } ?>
  </tbody>
</table>

</br>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
