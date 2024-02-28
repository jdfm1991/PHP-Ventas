<?php
require("conexion.php");
require("modelo.php");
$modelo = new Modelo();
require_once("funciones.php");
$descip = $_GET['descrip'];
session_start();
set_time_limit(0);
if ($_SESSION['open'] == "") {
	echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
$codvend = $_SESSION['open'];
$sql = "IF NOT EXISTS (SELECT * FROM sysobjects WHERE type = 'U' AND name = 'SAUX')
		BEGIN
	CREATE TABLE [dbo].[SAUX](
		[id_pedido] [int] IDENTITY(1,1) NOT NULL,
		[codvend] [varchar](10) COLLATE Modern_Spanish_CI_AS NULL,
		[codclie] [varchar](15) COLLATE Modern_Spanish_CI_AS NULL,
		[cliente] [varchar](15) COLLATE Modern_Spanish_CI_AS NULL,
		[precioclie] [int] NULL,
		[codprod] [varchar](15) COLLATE Modern_Spanish_CI_AS NULL,
		[descriprod] [varchar](40) COLLATE Modern_Spanish_CI_AS NULL,
		[unidad] [int] NULL,
		[precio] [decimal](28, 4) NULL,
		[esexcento] [int] NULL,
		[pedido] [decimal](28, 4) NULL,
		[linea] [int] NULL,
		[fecha] [datetime] NULL,
		[estatus] [int] NULL,
		[numerod] [varchar](20) COLLATE Modern_Spanish_CI_AS NULL
	) ON [PRIMARY]
	END"; {
	?>
<input type="hidden" name="idvend" id="idvend" value= <?php echo $codvend ?>>
	<script type="text/javascript">
		function volver_inicio() {
			location.href = "index.php";
		}
		function ver_clie(code) {
			location.href = "index.php?page=clientes_det&mod=1&codclie=" + code + "&descri=<?php echo $descip; ?>";
		}

		function lista_prod() {
			var instan = document.getElementById("instancia").value;
			var chec = 0;
			if (document.getElementById('exist_cero').checked == true) {
				chec = 1;
			}
			if (instan != "-") {
				var codeclie = document.getElementById("clientes").value;
				vend = $.trim($('#idvend').val());
				if ($("#clientes").val().length>0) {

					$.ajax({
					type: "POST",
					url: "client_searh.php",
					dataType: "json",
					data:  {search:codeclie, vend:vend},
					success: function (data) {
							$('#datalistOptions').empty();
							$.each(data, function(idx, opt) {
								
								document.getElementById('lista_productos').style.visibility = 'visible';
								document.getElementById('preload').style.visibility = 'visible';
								$("#operaciones").html("");
								$.post("pedidos_lista.php", { marca: instan, check: chec, codclie: codeclie },
									function (data) {
										$("#lista_productos").html(data);
									});

							});
						
						}
					});

					
				} else {
					document.getElementById('instancia').selectedIndex = 0;
					document.getElementById('instancia').value = '-';
					alert("DEBE ASIGNAR UN CLIENTE");
					document.getElementById('preload').style.visibility = 'hidden';
					document.getElementById('lista_productos').style.visibility = 'hidden';
				}
			}
		}
		function guardar_pedido() {
			if ($("#clientes").val().length>0) {
				document.getElementById('preload').style.visibility = 'visible';
				document.getElementById('lista_productos').style.visibility = 'visible';

				$.post("pedidos_inserta.php", $("#formlista").serialize(),
					function (data) {
						$("#operaciones").html(data);
					});

				$("#lista_productos").html("");
			} else {
				alert("DEBE ASIGNAR UN CLIENTE");
				document.getElementById('preload').style.visibility = 'hidden';
				document.getElementById('lista_productos').style.visibility = 'hidden';
			}
		}

		function pedido_clie() {
			var codeclie = document.getElementById("clientes").value;
			var elem = codeclie.split(';');
			if (elem[0] != 'BlOQUEADO') {
				document.getElementById('preload').style.visibility = 'visible';
				document.getElementById('lista_productos').style.visibility = 'visible';
				$("#instancia").val('-');
				$("#instancia").change();
				$("#operaciones").html("");
				$.post("pedidos_cliente.php", { codclie: codeclie },
					function (data) {
						$("#lista_productos").html(data);
					});
			} else {
				alert('EL CLIENTE: ' + elem[1] + ' ' + elem[2] + '. HA SIDO BLOQUEADO. MOTIVO: ' + elem[3]);
				document.getElementById('clientes').selectedIndex = 0;
				document.getElementById('clientes').value = '-';
			}
		}

		function pedido_clie_2() {
			var codeclie = document.getElementById("clientes").value;
			document.getElementById('preload').style.visibility = 'visible';
			document.getElementById('lista_productos').style.visibility = 'visible';
			$("#instancia").val('-');
			$("#instancia").change();
			if ($("#clientes").val().length>0) {
				$("#operaciones").html("");
				$.post("pedidos_cliente.php", { codclie: codeclie },
					function (data) {
						$("#lista_productos").html(data);
					});
			} else {
				alert("DEBE COLOCAR EL CLIENTE");
				document.getElementById('preload').style.visibility = 'hidden';
			}

		}

		function reinicio() {
			setTimeout(function () {
				window.location.href = "index.php";
			}, 4000);
			$("#instancia").val('-');
			$("#instancia").change();
			pedido_clie();
		}
		function borra_item(ida) {
			document.getElementById('preload').style.visibility = 'visible';
			document.getElementById('lista_productos').style.visibility = 'visible';
			$.post("pedidos_del.php", { id: ida },
				function (data) {
					$("#operaciones").html(data);
				});
		}
		function finaliza_pedido(n, codeclie, nota) {
			if (n > 0) {
				var nota = document.getElementById("nota").value;
				document.getElementById('preload').style.visibility = 'visible';
				document.getElementById('lista_productos').style.visibility = 'visible';
				$.post("pedidos_finaliza.php", { codclie: codeclie, nota: nota },
					function (data) {
						$("#operaciones").html(data);
					});
				$("#lista_productos").html("");
			} else {
				alert("DEBE HABER ELEGIDO POR LO MENOS UN PRODUCTO");
			}
		}
		function prehide() {
			if (document.getElementById) {
				document.getElementById('preload').style.visibility = 'hidden';
			}
		}

		function searchClient() {
		vend = $.trim($('#idvend').val());
	
	$('#clientes').keyup(function (e) {
		search = $(this).val(); 
		
		if(search.length==0){
			$("#descripcion").text("");
			document.getElementById('instancia').selectedIndex = 0;
			document.getElementById('instancia').value = '-';
			document.getElementById('preload').style.visibility = 'hidden';
			document.getElementById('lista_productos').style.visibility = 'hidden';

		}else{

					var datos = new FormData();
    
					datos.append('search', search);
					datos.append('vend', vend);

					$.ajax({
					type: "POST",
					url: "client_searh.php",
					dataType: "json",
					data:  {search:search, vend:vend},
					success: function (data) {
						$('#datalistOptions').empty();
						$.each(data, function(idx, opt) {
							if (opt.escredito == 1) {
								$('#datalistOptions').append('<option value="' + opt.codclie +'">' + opt.descrip + '</option>');
								$("#descripcion").text(opt.descrip);

							} else {
								alert('Cliente BLOQUEADO: '+ opt.codclie +' ; MOTIVO: '+ opt.observa);
								$('#datalistOptions').val('');
								$("#descripcion").text("");
								
							}
							

						});
					
					}
				});

		}

	});
	
}
searchClient()

	</script>
	<table width="auto" data-role="header" data-theme="b" border="0">
		<tr>
			<td width="196"><strong>EDV:</strong>
				<?php echo $codvend; ?>
			</td>
			<td width="269"><strong>FECHA
					<?php echo date("d/m/Y"); ?>
				</strong></td>
		</tr>
		<tr>
			<td><a href="javascript:;" onclick="pedido_clie_2()">VER PEDIDO ACTUAL</a></td>
			<td><a href="index.php?page=pedidos_busca&mod=1">PEDIDOS POR FACTURAR</a></td>
		</tr>
		<tr>
			<td><a href="index.php?page=pedidos_espera&mod=1">PEDIDOS EN ESPERA </a></td>
			<td><a href="index.php?page=pedidos_busca_fact&mod=1">PEDIDOS FACTURADOS </a></td>
		</tr>
		<tr>
			<?php
			if ($_SESSION['open'] == '01') { ?>
				<td><a href="index.php?page=relacionEDV_busca&mod=1">RELACION VENDEDOR</a></td>
			<?php } else { ?>
				<td><a href=""></a></td>
			<?php } ?>
			<td><a href="index.php?page=gestion_ver&mod=1">ENVIAR GESTION</a></td>
		</tr>
		<tr class="ui-state-default">

			<td colspan="2"> EXISTENCIA: <img src="img/si.png" width="15" height="15" border="0" />&nbsp;&nbsp; SIN
				EXISTENCIA: <img src="img/no.png" width="15" height="15" border="0" /></td>
		</tr>
	</table>
	<?php
	$Contadorclie = 0;

	$datos = $modelo->consultaSQL("select saclie.codclie, descrip, escredito, observa from saclie where (SACLIE.CodVend = '$codvend' ) and CodSucu='00000' order by descrip");
	$codclie = $descrip = $clientes = $escredito = $observa = '';
	foreach ($datos as $row) {
		$Contadorclie++;
	}
	?>

<?php
	if ($Contadorclie != 0) { ?>
		<input class="form-control" list="datalistOptions" id="clientes" placeholder="Razon Social A Consultar">
		<datalist id="datalistOptions">
            
        </datalist>
		
		<b>
          <p style="color:#FF0000; text-align:center;" ; id='descripcion'></p>
        </b>
		<?php 	
		}else{
		echo "NO HAY REGISTROS";
		}
	?>
	<label>
		<input type="checkbox" id="exist_cero" onChange="lista_prod()" checked="checked" name="checkbox-0">Productos Con
		Existencia
	</label>
	<select name="instancia" id="instancia" onChange="lista_prod()">
		<option value="-">Lista de Instancias (MARCAS)</option>
		<?php


			$consulta = $modelo->consultaSQL("SELECT distinct  sainsta.CodInst, sainsta.Descrip
			from sainsta 
			inner join saprod on saprod.CodInst = sainsta.CodInst
			inner join saexis on saexis.CodProd = saprod.CodProd 
			where (saexis.codubic = '01') and (saexis.Existen >= 0 or saexis.ExUnidad>=0) and saexis.CodSucu='00000' order by sainsta.Descrip");


		foreach ($consulta as $row){ ?>
			<option value="<?php echo $row["CodInst"]; ?>"><?php echo utf8_decode($row["Descrip"]); ?></option>
		<?php } ?>
	</select>
	<marquee id="preload" align="absmiddle" style="visibility:hidden;background-color:#999999;">
		| | | | | | | | | | | | | | | PROCESANDO . . . . . POR FAVOR ESPERE . . . .</marquee>
	<div id="lista_productos">
	</div>

	<p><button class="hide-page-loading-msg" data-inline="true" data-icon="back" onClick="volver_inicio();">Volver</button>
	</p>
	<div id="operaciones"></div>
	<?php
}
?>
