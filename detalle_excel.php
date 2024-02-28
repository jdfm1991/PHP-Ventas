<?php
require("conexion.php");
require_once('jpgraph4.3.4/src/jpgraph.php');
require_once('jpgraph4.3.4/src/jpgraph_bar.php');
require_once('jpgraph4.3.4/src/jpgraph_line.php');

require('vendor/autoload.php');
require("Excel.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

require("modelo.php");
$modelo = new Modelo();

$numerod = $_GET['numd'];
$tipo = $_GET['tipo'];
$tipofac = $_GET['tipo'];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
foreach (range('A', 'G') as $columnID) {
  $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Logo
$gdImage = imagecreatefrompng('img/logoF.png');
$objDrawing = new MemoryDrawing();
$objDrawing->setName('Sample image');
$objDrawing->setDescription('TEST');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
$objDrawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(108);
$objDrawing->setWidth(128);
$objDrawing->setCoordinates('E1');
$objDrawing->setWorksheet($spreadsheet->getActiveSheet());

/** DATOS DEL REPORTE **/
$spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFont()->setSize(25);


  $sheet->setCellValue('A1', 'DETELLE DEL PEDIDO - '. $numerod);

$sheet->setCellValue('A5', 'Fecha de Reporte:  ' . date('d-m-Y'));

$spreadsheet->getActiveSheet()->mergeCells('A1:C1');

/** TITULO DE LA TABLA **/
$sheet->setCellValue('A7', utf8_decode("Codigo"))
  ->setCellValue('B7', utf8_decode('Descripicion'))
  ->setCellValue('C7', utf8_decode('Cantidad'))
  ->setCellValue('D7', utf8_decode('Unidad'))
  ->setCellValue('E7', utf8_decode('Monto'));

$style_title = new Style();
$style_title->applyFromArray(
  Excel::styleHeadTable()
);


//estableceer el estilo de la cabecera de la tabla
$spreadsheet->getActiveSheet()->duplicateStyle($style_title, 'A7:E7');

function rdecimal($valor)
{
  $float_redondeado = round($valor * 100) / 100;
  return $float_redondeado;
}


if ($tipo == "A") {
  $tipo = 'FACT ';
}
if ($tipo == "10") {
  $tipo = 'FACT ';
  $tipofac = 'A';
}
if ($tipo == "B") {
  $tipo = 'DEV ';
}
if ($tipo == "20") {
  $tipo = 'N/D ';
  $tipofac = 'B';
}
if ($tipo == "F") {
  $tipo = 'PEDIDO ';
  $tipofac = 'F';
}
$consult_fact = $modelo->consultaSQL("select numerod, safact.codvend as vendedor, safact.codclie as codcliente, safact.descrip as cliente, safact.fechae as fechaemi, mtototal, monto, descto1, mtotax  from safact inner join saclie on  safact.codclie = saclie.codclie where numerod = '$numerod' and tipofac = '$tipofac' and safact.CodSucu='00000'");
$mtototal = $mtotax = $descto1 = $monto = 0;
$codcliente = $cliente = '';
foreach ($consult_fact as $row) {
  $codcliente = $row["codcliente"];
  $cliente = $row["cliente"];
  $monto = $row["monto"];
  $descto1 = $row["descto1"];
  $mtotax = $row["mtotax"];
  $mtototal = $row["mtototal"];
}

$consult_fact_items = $modelo->consultaSQL("select * from saitemfac where numerod = '$numerod' and tipofac = '$tipofac' and CodSucu='00000' order by nrolinea");
$contador = 0;
foreach ($consult_fact_items as $row) {
  $contador++;
}

$num = ($contador);

$row = 8;
foreach ($consult_fact_items as $row11) {

  $sheet = $spreadsheet->getActiveSheet();
  $sheet->setCellValue('A' . $row, utf8_encode($row11["CodItem"]));
  $sheet->setCellValue('B' . $row, utf8_encode($row11["Descrip1"]));
  $sheet->setCellValue('C' . $row, $row11["Cantidad"]);
  if ($row11["EsUnid"] == 1){
    $sheet->setCellValue('D' . $row, "Uni");
	}else{
    $sheet->setCellValue('D' . $row, "Paq");
  }
 
  $sheet->setCellValue('E' . $row, number_format($row11["TotalItem"],2));
 

  /** centrar las celdas **/
  $spreadsheet->getActiveSheet()->getStyle('A' . $row)->applyFromArray(array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrap' => TRUE)));
  $spreadsheet->getActiveSheet()->getStyle('B' . $row)->applyFromArray(array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrap' => TRUE)));
  $spreadsheet->getActiveSheet()->getStyle('C' . $row)->applyFromArray(array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrap' => TRUE)));
  $spreadsheet->getActiveSheet()->getStyle('D' . $row)->applyFromArray(array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrap' => TRUE)));
  $spreadsheet->getActiveSheet()->getStyle('E' . $row)->applyFromArray(array('alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrap' => TRUE)));
  
  $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="DETELLE DEL PEDIDO - '.$numerod.'.xlsx"');
header('Cache-Control: max-age=0');


$writer = new Xlsx($spreadsheet);
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$callStartTime = microtime(true);
ob_end_clean();
ob_start();
$writer->save('php://output');