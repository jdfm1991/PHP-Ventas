<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<script src="js/md5.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	/*haremos una peque�a funci�n para mostrar c�mo funciona */
	function ejemplo(texto){
		var segura=calcMD5(texto);//encripto la cadena usando la funci�n calcMD5()
		alert("La cadena "+texto+" encriptada es="+segura);//muestro la cadena encriptada
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
