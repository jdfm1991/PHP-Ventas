<?php 
session_start();
error_reporting(0);
header( 'Content-Type: text/html;charset=utf-8' ); 
?>
<!DOCTYPE html>
<html>
	<head>
		<!--<meta charset="utf-8">-->
		<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
		<META http-equiv=Content-Type content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>..::OSSY - Online Sale System::..</title>
		<link rel="stylesheet" href="themes/mobile.min.css" />
		<link rel="stylesheet" href="mobile/jquery.mobile.structure-1.3.2.min.css" />
		<script src="mobile/jquery-1.9.1.min.js"></script>
		<script src="mobile/jquery.mobile-1.3.2.min.js"></script>
		<script src="mobile/jquery.mobile.datepicker.js"></script>
		<script src="mobile/jquery.ui.datepicker.js"></script>
		<script src="md5/js/md5.js" type="text/javascript"></script>
		
		
		<link rel="stylesheet"  href="css-azul/smoothness/jquery-ui-1.10.4.custom.css">
    <script src="jquery/ui/jquery.ui.core.js"></script>
	<script src="jquery/ui/jquery.ui.widget.js"></script>
	<script src="jquery/ui/jquery.ui.datepicker.js"></script>
	<script src="jquery/ui/jquery.ui.mouse.js"></script>
	<script src="jquery/ui/jquery.ui.draggable.js"></script>
		
		<script type="text/javascript">
		function acces(){
			var lo =  calcMD5(document.getElementById('usuario').value).toLowerCase();
			var pa =  calcMD5(document.getElementById('clave').value).toLowerCase();
			document.getElementById("envio").action= "index.php?page=acceso&mod=1";
			document.envio.submit() 
		}
		function mayus(e) {
			e.value = e.value.toUpperCase();
		}
		</script>
	</head>
	<body>
		<div data-role="page" data-theme="a">
			<div data-role="header" data-position="inline">
				<h1>Ossy Confiteria Guayana <?php if ($_SESSION['open'] != ""){ echo "EDV: ".$_SESSION['open'];}  ?> </h1>
			</div>
			<div data-role="content" data-theme="a">

					<table width="978" class="Estilo2" height="" border="0" colspan="16">
						<thead>
								<tr>
									<td style="width:150px;">
										<img src="img/logo.png" alt="" width="230" height="95" border="0" />
									</td>
								</tr>
						</thead>
					</table>

				<?php if ($_SESSION['open'] == ""){  ?>		
				<p><strong>DISTRIBUIDORA CONFITERIA GUAYANA - COSTA AMERICA </strong></p>	
				<form action="" method="post" id="envio" name="envio">
				  <p>Usuario
				    <input onkeyup="mayus(this);" placeholder="" required name="usuario" type="text" id="usuario" maxlength="10">
				Contraseña
				<input name="clave" type="password"  id="clave" maxlength="20">
				<input type="button"  onClick="acces();" value="Entrar"> 
				  </p>
				</form>
				<?php } ?>
				<?php if ($_SESSION['open'] != "" and $_GET['mod'] == ""){ ?>
				<ul data-role="listview">
				<!--<li><a href="index.php?page=vendedor_fact&mod=1">Cuentas por Cobrar Facturas EDV </a></li>
				<li><a href="index.php?page=vendedor_ne&mod=1">Cuentas por Cobrar Notas de Entregas EDV </a></li>	
				<li><a href="index.php?page=estadisticas_fact&mod=1">Estadísticas EDV FACT</a></li>
				<li><a href="index.php?page=estadisticas_ne&mod=1">Estadísticas EDV NE</a></li>
				<li><a href="index.php?page=productos&mod=1">Consulta Productos</a></li>-->
				<br>
				<li><a href="index.php?page=pedidos&mod=1">Pedidos</a></li>
				</ul>
				<?php } ?>
				
				<?php 
				if (isset($_GET['page']) && isset($_GET['mod'])){ 
			          if ($_GET['page'] && $_GET['mod']){
                          switch ($_GET['mod']) {
                            case '1':
								include("".$_GET['page'].".php"); 							 
							break;
						}
					}
				}	
				?>
			</div>
			<div data-role="footer" class="ui-bar" align="center">
    <a href="index.php?page=close&mod=1" data-icon="arrow-d">Cerrar</a>
	</div>
		</div>
		
		
				
			
	</body>
</html>