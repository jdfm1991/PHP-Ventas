<?php
function decimal($val){
return number_format($val, 2, ",", ".");
}
function rdecimal($valor) {
   $float_redondeado=round($valor * 100) / 100;
   return $float_redondeado;
}
function normalize_date($date){ //VENESUR
		 if(!empty($date)){
			 $var = explode('/',str_replace('-','/',$date));
			 return "$var[2]-$var[1]-$var[0]";
			 return $date;
		 }
	}
function dias($fechaf){
putenv("TZ=America/Caracas");
$fecha = date("d/m/Y");
$datetime=$fechaf;
$dt = strtotime($datetime);
$nuevav = date("d/m/Y", $dt);

$resultado1 = (int)(strtotime($fecha));
$resultado2 = (int)(strtotime($nuevav));
$resultado3 = $resultado1 - $resultado2;
$dias = $resultado3 / 60 /60 /24;
return $dias;
}
function valida_dia($dia){
  switch ($dia) {
    case "Mon":
        return "Lunes";
        break;
    case "Tue":
        return "Martes";
        break;
    case "Wed":
        return "Miércoles";
        break;
    case "Thu":
        return "Jueves";
        break;
    case "Fri":
        return "Viernes";
        break;
    case "Sat":
        return "Sábado";
        break;
    default:
        return "Lunes";
        break;
  }
}
?>