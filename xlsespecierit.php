<?php 
/** Incluir la libreria PHPExcel */
require_once("../PHPExcel/Classes/PHPExcel.php");
//require_once("../PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
 
 
// Crea un nuevo objeto PHPExcel 
$objPHPExcel = new PHPExcel(); 
 
 
// Establecer propiedades 
$objPHPExcel->getProperties() 
->setCreator("Marcelo Mujica") 
->setLastModifiedBy("MMujica") 
->setTitle("Documento Excel Participantes") 
->setSubject("Query ORACLE Participantes") 
->setDescription("Participantes") 
->setKeywords("Excel Office 2007 openxml php") 
->setCategory("Query SIAGJ"); 
 
 
$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$ritesp=trim(strtoupper($_GET['ritesp']));
$anoesp=trim(strtoupper($_GET['anoesp']));

$querypass="
SELECT /*+ ORDERED */ TCUS_REPMOVESP.IDF_ROLUNICO, 
       TATP_CAUSA.IDF_ROLINTERNO, 
       TATP_CAUSA.FEC_ERA, 
       TCUS_REPMOVESP.GLS_ESPECIE, 
       TCUS_REPMOVESP.NUM_CANTIDAD,
	   TO_CHAR(TCUS_REPMOVESP.FEC_ASIGNADA, 'dd/mm/yyyy hh24:mi:ss'),
   	   TO_CHAR(TCUS_REPMOVESP.FEC_SISTEMA, 'dd/mm/yyyy hh24:mi:ss'),
       TCUS_REPMOVESP.GLS_TIPMOVIMIENTO, 
       TCUS_REPMOVESP.IDF_CODESPECIE,
	   TCUS_REPMOVESP.CRR_MOVIMIENTO,
   	   TCUS_REPMOVESP.CRR_MOVIMIENTOSAL

FROM JUDPENAL.TCUS_REPMOVESP TCUS_REPMOVESP 
INNER JOIN 
JUDPENAL.TATP_CAUSA TATP_CAUSA 
ON (TCUS_REPMOVESP.IDF_ROLUNICO = TATP_CAUSA.IDF_ROLUNICO) 
AND (TCUS_REPMOVESP.IDF_ROLINTERNO = TATP_CAUSA.IDF_ROLINTERNO) 
WHERE (TATP_CAUSA.IDF_ROLINTERNO = $ritesp ) 
AND (TATP_CAUSA.FEC_ERA = $anoesp) 
AND (TATP_CAUSA.COD_TRIBUNAL = 953)";



$stid = oci_parse($conn,$querypass);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentiaties($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en query";
	
}
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error al ejecutar query";
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'RUC')
->setCellValue('B1', 'RIT')
->setCellValue('C1', utf8_encode('AÑO'))
->setCellValue('D1', 'ESPECIE')
->setCellValue('E1', 'CANTIDAD')
->setCellValue('F1', 'F.INGRESO')
->setCellValue('G1', 'F.EGRESO')
->setCellValue('H1', 'MOVIMIENTO')
->setCellValue('I1', 'COD.ESPECIE')
->setCellValue('J1', 'CRR MOV.')
->setCellValue('K1', 'CRR MOV. SAL.')
;
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getNumberFormat();

$fila=2;
$col=0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {	
    foreach ($row as $item) {

		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($col,$fila,utf8_encode($item));
		$col++;
    }
	$col=0;
	$fila++;
}
 
//Autofiltro, BOLD
$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

// Renombrar Hoja 
$objPHPExcel->getActiveSheet()->setTitle('Evento'); 

 
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero. 
$objPHPExcel->setActiveSheetIndex(0); 
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel. 
$archivo ="Especies ". date("d-m-Y"). ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header("Content-Disposition: attachment; filename=\"$archivo\"");
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output'); 


oci_free_statement($stid);
oci_close($conn);

exit; 
?>