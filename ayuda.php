<?php 
require("conexion.php");
session_start();
if ($_SESSION['open'] == ""){
echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
?>
<script type="text/javascript">
function volver(){
location.href = "index.php";
}
</script>
<div class="ui-btn-active">
  <p>AppMobile</p>
  <p>Aplicaci&oacute;n dise&ntilde;anda por el Departamento de IT del Grupo Confisur para facilitar la Gesti&oacute;n de los procesos de Ventas dentro de la entidad, permitiendo asi visualizar: </p>
  <ul>
    <li>Estado de Cuenta de nuestros Clientes: La aplicaci&oacute;n permite que los EDV (Ejecutivos de Ventas) accedan a la informaci&oacute;n completa de los clientes, desde su saldo hasta las facturas que tienen pendientes por Pagar</li>
    <li>Activaciones: AppMobile permite a los EDV  (Ejecutivos de Ventas) visualizar la activaciones o cantidad de ventas al mes que han tenido nuestros clientes permitiendo el c&aacute;lculo de comisiones por activaci&oacute;n de clientes.</li>
    <li>Cuentas por Cobrar: La Aplicaci&oacute;n permite mostrar las facturas pendientes por cobrar que posee un EDV  (Ejecutivos de Ventas) seg&uacute;n su cartera de Clientes.</li>
    <li>Ventas: Permite Visualizar las Ventas que ha realizado el EDV  (Ejecutivos de Ventas) en un periodo determinado.</li>
    <li>Pedidos: AppMobile Permite generar pedidos directamente en el Servidor Saint   mostrando los clientes por EDV  (Ejecutivos de Ventas) y los distintos productos que puede ofrecerle a su cartera de clientes, agilizando asi el proceso de facturaci&oacute;n y generaci&oacute;n de pedidos dentro de la entidad.</li>
    <li>Productos: La Aplicaci&oacute;n despliega una lista de nuestros productos clasificados por Marca, mostrando asi la existencia Real del Producto y el precio actual que posee cada SKU.</li>
    <li>Cat&aacute;logos: Muestra los distintos Cat&aacute;logos que manejan los EDV  (Ejecutivos de Ventas) para muestra de nuestros Productos a nuestros Clientes. </li>
  </ul>
  <p>Para M&aacute;s Informaci&oacute;n o Asesoria T&eacute;cnica Puede Comunicarse con el Dpto IT. </p>
  <p>Correos: apalomo@gconfisur.com - gramirez@gconfisur.com</p>
  <p>Tel&eacute;fonos: <a href="tel:04269807212">04269807212</a> - <a href="tel:04148930474">04148930474</a></p>
  <p><a href="manuales/Manual de usuario generacion de pedidos.pdf" target="_blank">Manual Pedidos AppMobile</a>  </p>
  <p>&nbsp;</p>
</div>

<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver()">Volver</button></p>
