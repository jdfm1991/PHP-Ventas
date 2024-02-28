<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<script src="js/base_64.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	/*haremos una peque&ntilde;a funci&oacute;n para mostrar c&oacute;mo funciona */
	function ejemplo(texto){
		var encripta=Base64.encode(texto);//encripto la cadena
		var des=Base64.decode(encripta);//desencripto la cadena
		alert("La cadena "+texto+" encriptada es="+encripta);//muestro la cadena encriptada
		alert("La cadena "+encripta+" desencriptada es="+des);//muestro la cadena desencriptada
	}
</script>
</head>

<body>
<form name="form" action="md5_js.php" method="get">
Ingrese un texto:<input type="text" name="name" />
<br />
<input type="button" value="Encriptar" title="Encriptar a md5" onClick="ejemplo(document.form.name.value)" />
</form>
</body>
</html>
