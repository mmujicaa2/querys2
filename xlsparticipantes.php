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
$ritpart=trim(strtoupper($_GET['ritpart']));
$anopart=trim(strtoupper($_GET['anopart']));

$querypass="
SELECT
TATP_CAUSA.IDF_ROLINTERNO, 
TATP_CAUSA.FEC_ERA,
TATP_PERSONA.IDF_NOMBRES,
TATP_PERSONA.IDF_PATERNO,
TATP_PERSONA.IDF_MATERNO,
TING_DIRECCION.NOM_CALLE,
TING_DIRECCION.NUMERO
FROM ( ( JUDPENAL.TING_DIRECCION TING_DIRECCION 
INNER JOIN 
JUDPENAL.TATP_PERSONA TATP_PERSONA
ON (TING_DIRECCION.CRR_PERSONA = TATP_PERSONA.CRR_LITIGANTE_ID)) 
INNER JOIN 
JUDPENAL.TATP_PARTICIPANTE TATP_PARTICIPANTE
ON (TATP_PARTICIPANTE.CRR_PERSONA = TATP_PERSONA.CRR_LITIGANTE_ID)) 
INNER JOIN 
JUDPENAL.TATP_CAUSA TATP_CAUSA
ON (TATP_PARTICIPANTE.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA) 
WHERE (TATP_CAUSA.IDF_ROLINTERNO = $ritpart) 
AND (TATP_CAUSA.FEC_ERA = $anopart) 
AND (TATP_CAUSA.COD_TRIBUNAL = 953)
AND (TATP_CAUSA.TIP_CAUSAREF=1)


";




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
->setCellValue('A1', 'RIT')
->setCellValue('B1', utf8_encode('AÑO'))
->setCellValue('C1', 'NOMBRES')
->setCellValue('D1', 'APATERNO')
->setCellValue('E1', 'AMATERNO')
->setCellValue('F1', 'CALLE')
->setCellValue('G1', 'NUMERO')
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
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

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