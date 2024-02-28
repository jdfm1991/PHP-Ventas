<?php
require_once("conexion.php");
require('fpdf/fpdf.php');
require("modelo.php");
$modelo = new Modelo();
session_start();
set_time_limit(0);
if ($_SESSION['open'] == "") {
    echo "<script language=Javascript> location.href=\"close.php\";</script>";
}

$ruta = $_GET['edv'];

$edv = $modelo->consultaSQL("SELECT Descrip, clase from SAVEND where CodVend = '$ruta'");

foreach ($edv as $row) {
    $Descrip = $row['Descrip'];
    $clase = $row['clase'];
}

$actual = date("d/m/Y");
$day = date("l");
switch ($day) {
    case "Sunday":
        $days = "Domingo";
        break;
    case "Monday":
        $days = "Lunes";
        break;
    case "Tuesday":
        $days = "Martes";
        break;
    case "Wednesday":
        $days = "Miercoles";
        break;
    case "Thursday":
        $days = "Jueves";
        break;
    case "Friday":
        $days = "Viernes";
        break;
    case "Saturday":
        $days = "Sabado";
        break;
}

$clientes_f = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT b.CodClie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip as ciudad , (b.MtoTotal/b.Factor) as total , a.codvend from SAITEMFAC as a inner join SAFACT as b on a.NumeroD = b.numerod inner join SACLIE as c on b.CodClie = c.CodClie inner join SACIUDAD as d on c.Ciudad =d.Ciudad where  a.TipoFac in ('F') and b.TipoFac in ('F') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf
	and a.CodVend ='$ruta' and a.CodSucu='00000' group by b.Codclie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip , b.MtoTotal, b.Factor,a.codvend, b.numerod order by a.numerod
	");

$clientes_a_c = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT b.CodClie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip as ciudad , (b.MtoTotal/b.Factor) as total , a.codvend from SAITEMFAC as a inner join SAFACT as b on a.NumeroD = b.numerod inner join SACLIE as c on b.CodClie = c.CodClie inner join SACIUDAD as d on c.Ciudad =d.Ciudad where  a.TipoFac in ('C','A') and b.TipoFac in ('C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf
	and a.CodVend ='$ruta' and a.CodSucu='00000' group by b.Codclie, b.Descrip , a.NumeroD, a.TipoFac, d.Descrip , b.MtoTotal, b.Factor,a.codvend, b.numerod order by a.numerod
	");

$clientes_detalles = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select DISTINCT CodItem, Descrip1 from
	SAITEMFAC where  TipoFac in ('F','C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and CodVend ='$ruta' and CodSucu='00000' group by  CodItem, Descrip1
	");

$bultos = 0;
$paquetes = 0;
$bultos1 = 0;
$paquetes1 = 0;
$tbultos = 0;
$tpaquetes = 0;
$ttotal = 0;

$tbultos_f = 0;
$tpaquetes_f = 0;
$ttotal_f = 0;

$tbultos_a_c = 0;
$tpaquetes_a_c = 0;
$ttotal_a_c = 0;
//-------------------------------------------------------------
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
$time = date("d-m-Y h:i:s a");


$j = 0;
$width = array();
function addWidthInArray($num)
{
    $GLOBALS['width'][$GLOBALS['j']] = $num;
    $GLOBALS['j'] = $GLOBALS['j'] + 1;
    return $num;
}

function rdecimal($number, $precision = 2, $separator = '.')
{
    $numberParts = explode($separator, $number);
    $response = $numberParts[0];
    if (count($numberParts) > 1) {
        $response .= $separator;
        $response .= substr(
            $numberParts[1],
            0,
            $precision
        );
    }
    return $response;
}

class PDF extends FPDF
{
    var $widths;
    var $aligns;

    // Cabecera de página
    function Header()
    {
        $modelo = new Modelo();

        $empresa = $modelo->consultaSQL("SELECT Descrip FROM SACONF where CodSucu='00000'");

        $time = date("d-m-Y h:i:s a");

        // Logo
        $this->Image('img/logo.png', 10, 8, 33);
        // Arial bold 15
        $this->SetFont('Arial', '', 12);

        // datos empresa
        $this->Cell(80);
        foreach ($empresa as $row) {
            $this->Cell(30, 8, utf8_decode($row['Descrip']), 0, 1, 'C');

        }
        $this->Cell(80);
        $this->Cell(30, 8, 'Pedidos Realizados ' . $time, 0, 0, 'C');

        //linea
        $this->Line(8, 28, 200, 28);
        $this->Ln(13);
    }
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(211, 211, 211);
$multiplicador_linea = 0;

//datos del cliente
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 6, "Vendedor: ", 0, 0, 'L');
$pdf->SetFont('');
$pdf->Cell(100, 6, $ruta . ' ' . ($Descrip), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 6, "Canal: ", 0, 0, 'L');
$pdf->SetFont('');
$pdf->Cell(40, 6, ($clase), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 6, "Fecha: ", 0, 0, 'L');
$pdf->SetFont('');
$pdf->Cell(20, 6, $actual, 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(7, 6, "Dia:  ", 0, 0, 'L');
$pdf->SetFont('');
$pdf->Cell(20, 6, $days, 0, 1, 'L');
$pdf->Ln(3);


#################################################
#                                               #
#              Gestión Diaria                   #
#                                               #
#################################################
// titulo Gestión Diaria
$pdf->Cell(80);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30, 8, utf8_decode('Gestión Diaria'), 0, 1, 'C');
$pdf->Ln(3);

$pdf->Cell(80);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 8, utf8_decode('Presupuestos'), 0, 1, 'C');
$pdf->Ln(2);

// titulo de columnas Gestión Diaria presupuestos
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, '# Doc', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, 'Documento', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(17, 5, utf8_decode('Cód. Cliente'), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, utf8_decode('Razón Social'), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, 'Ciudad', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(12, 5, 'Paquetes', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(13, 5, 'Unidades', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(14, 5, 'Total $', 1, 1, 'C', true);

// contenido Gestión Diaria
$pdf->SetFont('Arial', '', 8);
$numerod = $tipofac = $edv = '';
foreach ($clientes_f as $row) {

    $numerod = $row['NumeroD'];
    $tipofac = $row['TipoFac'];
    $edv = $row['codvend'];

    $bultos = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('F') and EsUnid = 0 and  CodVend ='$ruta' and CodSucu='00000'");
    foreach ($bultos as $rowb) {
        $cantidadB = $rowb['cantidad'];
    }
    $paquetes = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('F') and EsUnid = 1 and CodVend ='$ruta' and CodSucu='00000'");
    foreach ($paquetes as $rowp) {
        $cantidadP = $rowp['cantidad'];
    }

    $tbultos_f = $tbultos_f + $cantidadB;
    $tpaquetes_f = $tpaquetes_f + $cantidadP;
    $ttotal_f = $ttotal_f + $row['total'];

    $multiplicador_linea += 5;

    $tipofac = $row['TipoFac'];
    $tipo = ($tipofac == 'F')
        ? "Presupuesto"
        : "";

    $pdf->Cell(13, 5, $numerod, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(24, 5, $tipo, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(17, 5, $row['CodClie'], '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(63, 5, $row['Descrip'], '', 0, 'L', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(24, 5, $row['ciudad'], '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(12, 5, number_format($cantidadB,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(13, 5, number_format($cantidadP,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(14, 5, number_format($row['total'],2), '', 1, 'R', 0);
}

//linea
$pdf->Line(8, $multiplicador_linea + 80, 200, $multiplicador_linea + 80);
$pdf->Ln(5);

// totales Gestión Diaria
$pedidos = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				select  COUNT(descrip) cuenta from SAFACT   where  TipoFac in ('F') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf
				and CodVend ='$ruta' and CodSucu='00000'");

foreach ($pedidos as $row78) {
    $cpedidos = $row78['cuenta'];
}

$maestro = $modelo->consultaSQL("SELECT count(CodClie) cuenta from  saclie  where CodVend ='$ruta' and Activo = '1' and CodSucu='00000'");

foreach ($maestro as $row78) {
    $cdia = $row78['cuenta'];
}


$clientesactivoss = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				select  count(DISTINCT Descrip) cuenta from SAFACT where TipoFac in ('F') and CodVend ='$ruta' and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and CodSucu='00000'");

foreach ($clientesactivoss as $row79) {
    $cact = $row79['cuenta'];
}


//EFECTIVIDAD BASADA EN LA CANTIDAD DE CLIENTES ACTIVADOS. (CLIENTES ACTIVADOS/CLIENTES DEL DIA * 100 = %) ESTE CASO APLICADA CUANDO SE HACEN MAS DE UN PEDIDO A UN CLIENTE EL MISMO DIA (CASO FRIO / SECO PARLAMAR)

$tefectividad = 0;
if ($cpedidos > 0 and $cdia != 0) {
    $tefectividad = ($cpedidos / $cdia) * 100;
} else {
    $tefectividad = 0;
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(57, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, "Cantidad de Pedidos: " . $cpedidos, '', 0, 'R', 0);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, "Total: ", '', 0, 'R', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(12, 5, $tbultos_f, '', 0, 'C', 0);
$tbultos += $tbultos_f;
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(13, 5, $tpaquetes_f, '', 0, 'C', 0);
$tpaquetes += $tpaquetes_f;
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(14, 5, number_format($ttotal_f,2), '', 1, 'R', 0);
$ttotal += $ttotal_f;
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(57, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, "Clientes del Dia: " . $cdia, '', 1, 'R', 0);
$pdf->Cell(57, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, "Clientes Activados: " . $cact, '', 1, 'R', 0);
$pdf->Cell(57, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, "Efectivdad: " . number_format($tefectividad,2) . " %", '', 1, 'R', 0);
$pdf->Ln(6);


$pdf->Ln(3);
$pdf->Cell(80);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 8, utf8_decode('Notas de Entrega y Factura'), 0, 1, 'C');
$pdf->Ln(2);

// titulo de columnas Gestión Diaria ne y fac
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, '# Doc', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, 'Documento', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(17, 5, utf8_decode('Cód. Cliente'), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(63, 5, utf8_decode('Razón Social'), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, 'Ciudad', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(12, 5, 'Paquetes', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(13, 5, 'Unidades', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(14, 5, 'Total $', 1, 1, 'C', true);

// contenido Gestión Diaria
$pdf->SetFont('Arial', '', 8);
foreach ($clientes_a_c as $row80) {

    $numerod = $row80['NumeroD'];
    $tipofac = $row80['TipoFac'];
    $edv = $row80['Codvend'];

    $bultos = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('C','A') and EsUnid = 0 and  CodVend ='$ruta' and CodSucu='00000'");

    foreach ($bultos as $row81) {
        $cantidadB = $row81['cantidad'];
    }

    $paquetes = $modelo->consultaSQL("SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where numerod = '$numerod' and TipoFac in ('C','A') and EsUnid = 1 and CodVend ='$ruta' and CodSucu='00000'");

    foreach ($paquetes as $row82) {
        $cantidadP = $row82['cantidad'];
    }


    $tbultos_a_c = $tbultos_a_c + $cantidadB;
    $tpaquetes_a_c = $tpaquetes_a_c + $cantidadP;
    $ttotal_a_c = $ttotal_a_c + $row80['total'];

    $multiplicador_linea += 5;

    $tipofac = $row80['tipofac'];
    $tipo = '';
    switch ($tipofac) {
        case "C":
            $tipo = "Nota de Entrega";
            break;
        case "A":
            $tipo = "Factura";
            break;
    }

    $pdf->Cell(13, 5, $numerod, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(24, 5, $tipo, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(17, 5, $row80['CodClie'], '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(63, 5, utf8_decode($row80['Descrip']), '', 0, 'L', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(24, 5, utf8_decode($row80['ciudad']), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(12, 5, number_format($cantidadB,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(13, 5, number_format($cantidadP,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(14, 5, number_format($row80['total'],2), '', 1, 'R', 0);
}

//linea
$pdf->Line(8, $multiplicador_linea + 130, 200, $multiplicador_linea + 130);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(121, 5, '', '', 0, 'C', 0);
$pdf->Cell(24, 5, "Total: ", '', 0, 'R', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(12, 5, $tbultos_a_c, '', 0, 'C', 0);
$tbultos += $tbultos_a_c;
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(13, 5, $tpaquetes_a_c, '', 0, 'C', 0);
$tpaquetes += $tpaquetes_a_c;
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(14, 5, number_format($ttotal_a_c,2), '', 1, 'R', 0);
$ttotal += $ttotal_a_c;
$pdf->Ln(6);

#################################################
#                                               #
#              Gestión por Marca                #
#                                               #
#################################################
// titulo Gestión por Marca
$pdf->Cell(80);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30, 8, utf8_decode('Gestión por Marca'), 0, 1, 'C');
$pdf->Ln(4);

$tbultos = $tpaquetes=0;

// titulo de columnas Gestión por Marca
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(62, 5, utf8_decode('Marca '), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(62, 5, 'Paquetes', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(62, 5, 'Unidades', 1, 1, 'C', true);

// contenido Gestión por Marca
$pdf->SetFont('Arial', '', 8);

$marcasactivas = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			select DISTINCT(b.Marca) as marca from SAITEMFAC as a inner join SAPROD as b on a.CodItem = b.CodProd where a.TipoFac in ('F','C','A') and
			DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf and a.CodVend ='$ruta' and a.CodSucu='00000' and Marca is not null");

foreach ($marcasactivas as $row83) {
    $marca = $row83["marca"];
    $itemxmarcas = $modelo->consultaSQL("DECLARE @fechai DATE
				DECLARE @fechaf DATE
				set @fechai = GETDATE()
				set @fechaf = GETDATE()
				SELECT saprod.Marca, sum((CASE WHEN EsUnid = '0' THEN Cantidad ELSE 0 END)) AS bult, sum((CASE WHEN EsUnid = '1' THEN Cantidad ELSE 0 END)) AS paq FROM saitemfac INNER JOIN saprod ON saitemfac.coditem = saprod.codprod INNER JOIN
				SAFACT ON SAITEMFAC.NumeroD = SAFACT.NumeroD WHERE SAFACT.CodSucu='00000' and
				DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN @fechai AND @fechaf AND saprod.marca LIKE '$marca' AND
				SAITEMFAC.codvend = '$ruta' AND saitemfac.TipoFac in ('F','C','A') AND SAFACT.TipoFac in ('F','C','A') AND SAFACT.NumeroD NOT IN
				(SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('F','C','A') AND x.NumeroR IS NOT NULL AND
				CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac = 'B') AS BIGINT)) GROUP BY saprod.Marca");

    foreach ($itemxmarcas as $row84) {
        $Marca = $row84["Marca"];
        $bult = $row84["bult"];
        $paq = $row84["paq"];
    }

    $tbultos += $bult;
	$tpaquetes +=$paq;

    $multiplicador_linea += 5;

    $pdf->Cell(62, 5, $Marca, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(62, 5, number_format($bult,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(62, 5, number_format($paq,2), '', 1, 'C', 0);
}

//linea
$pdf->Line(8, $multiplicador_linea + 162, 200, $multiplicador_linea + 162);
$pdf->Ln(3);

//totales Gestión por Marca
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(62, 5, "Total: ", '', 0, 'R', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(62, 5, $tbultos, '', 0, 'C', 0);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(62, 5, $tpaquetes, '', 1, 'C', 0);
$pdf->Ln(8);


#################################################
#                                               #
#              Gestión por Producto             #
#                                               #
#################################################
// titulo Gestión por Producto
$pdf->Cell(80);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30, 8, utf8_decode('Gestión por Producto'), 0, 1, 'C');
$pdf->Ln(4);

// titulo de columnas Gestión por Producto
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 5, 'SKU', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(90, 5, utf8_decode('Descripción'), 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(28, 5, 'Clientes Activos', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(19, 5, 'Paquetes', 1, 0, 'C', true);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(19, 5, 'Unidades', 1, 1, 'C', true);

// contenido Gestión por Producto
$pdf->SetFont('Arial', '', 8);
$tbultos = $tpaquetes=0;
foreach ($clientes_detalles as $row85) {
    $coditem = $row85["CodItem"];

    $cactivos = $modelo->consultaSQL("DECLARE @fechai DATE
	DECLARE @fechaf DATE
	set @fechai = GETDATE()
	set @fechaf = GETDATE()
	select  b.descrip from SAITEMFAC as a inner join safact as b on a.numerod = b.numerod where a.CodItem = '$coditem' and a.TipoFac in ('F','C','A') and DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) BETWEEN @fechai and @fechaf and a.CodVend ='$ruta' and a.CodSucu='00000'");

    $ContadorActivos = 0;
    foreach ($cactivos as $row86) {
        $ContadorActivos++;
    }
    $bultos1 = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where coditem = '$coditem' and TipoFac in ('F','C','A')  and EsUnid = 0 and  DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and codvend = '$ruta' and CodSucu='00000'");

    foreach ($bultos1 as $row87) {
        $cantidadB1 = $row87["cantidad"];
    }


    $paquetes1 = $modelo->consultaSQL("DECLARE @fechai DATE
			DECLARE @fechaf DATE
			set @fechai = GETDATE()
			set @fechaf = GETDATE()
			SELECT ISNULL(sum(cantidad),0) as cantidad from SAITEMFAC where coditem = '$coditem' and TipoFac in ('F','C','A')  and EsUnid = 1 and  DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf and codvend = '$ruta' and CodSucu='00000'");

    foreach ($paquetes1 as $row88) {
        $cantidadP1 = $row88["cantidad"];
    }
    $multiplicador_linea += 5;

    $tbultos += $cantidadB1;
	$tpaquetes +=$cantidadP1;

    $pdf->Cell(30, 5, $row85["CodItem"], '', 0, 'L', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(90, 5, utf8_decode($row85["Descrip1"]), '', 0, 'L', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(28, 5, $ContadorActivos, '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(19, 5, number_format($cantidadB1,2), '', 0, 'C', 0);
    $pdf->Cell(1, 5, '', '', 0, 'C', 0);
    $pdf->Cell(19, 5, number_format($cantidadP1,2), '', 1, 'C', 0);
}

//linea
$pdf->Line(8, $multiplicador_linea + 196, 200, $multiplicador_linea + 196);
$pdf->Ln(5);

//totales Gestión por Producto
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(146, 5, "Total: ", '', 0, 'R', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(4, 5, '', '', 0, 'C', 0);
$pdf->Cell(19, 5, $tbultos, '', 0, 'C', 0);
$pdf->Cell(1, 5, '', '', 0, 'C', 0);
$pdf->Cell(19, 5, $tpaquetes, '', 1, 'C', 0);


$pdf->Output();