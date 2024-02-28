<?php 
	require("conexion.php");
	require("modelo.php");
	$modelo = new Modelo();
	session_start();
	set_time_limit(0);
	$vend   = $_POST['vend'];
	$search = $_POST['search'];

	$saux_clientes = $modelo->consultaSQL("Select saclie.codclie, descrip, escredito, observa from saclie where (SACLIE.CodVend = '$vend' ) and (saclie.codclie LIKE '%$search%' OR Descrip LIKE '%$search%')  and CodSucu='00000' order by descrip");
	
	$dato = Array();
	foreach ($saux_clientes as $row) {

		$sub_array = array();
		$sub_array['codclie']   = $row['codclie'];
      	$sub_array['descrip']   = $row['descrip'];
		$sub_array['escredito'] = $row['escredito'];
		$sub_array['observa']   = $row['observa'];

		$dato[] = $sub_array;
		
	}

	echo json_encode($dato); 

?>
	