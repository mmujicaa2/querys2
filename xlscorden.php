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
->setTitle("Documento Excel Ordenes") 
->setSubject("Query ORACLE Ordenes") 
->setDescription("Ordenes de detenci�n") 
->setKeywords("Excel Office 2007 openxml php") 
->setCategory("Query SIAGJ"); 
 
 
$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}

$finicioev=trim(strtoupper($_GET['finicioev']));
$ffinev=trim(strtoupper($_GET['ffinev']));
$ctrib=trim(strtoupper($_GET['ctrib']));

$orden="TATP_PERSONA.IDF_NOMBRES ASC";
$querypass= "SELECT DISTINCT RIT.IDF_ROLUNICO, 
       RIT.IDF_ROLINTERNO, 
       RIT.FEC_ERA, 
       TGES_ORDENROL.IDF_ROLUNICOORDEN, 
       TATP_PERSONA.IDF_NOMBRES, 
       TATP_PERSONA.IDF_PATERNO, 
       TATP_PERSONA.IDF_MATERNO, 
       TATP_PERSONA.NUM_DOCID, 
       TGES_ORDENROL.FEC_SISTEMA,
     TGES_ORDEN.FLG_VIGENCIA,
       TGES_ORDEN.GLS_OBSERVACION 
  FROM (((JUDPENAL.TGES_ORDEN TGES_ORDEN 
       INNER JOIN JUDPENAL.TATP_PARTICIPANTE TATP_PARTICIPANTE 
          ON (TGES_ORDEN.CRR_PARTICIPANTE = TATP_PARTICIPANTE.CRR_IDPARTICIPANTE)) 
       INNER JOIN JUDPENAL.TGES_ORDENROL TGES_ORDENROL 
          ON (TGES_ORDENROL.CRR_ORDEN = TGES_ORDEN.CRR_IDORDEN)) 
       INNER JOIN JUDPENAL.TATP_PERSONA TATP_PERSONA 
          ON (TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA)) 
       INNER JOIN JUDPENAL.TATP_CAUSA RIT 
          ON (RIT.CRR_IDCAUSA = TATP_PARTICIPANTE.CRR_CAUSA) 
 WHERE (RIT.COD_TRIBUNAL = $ctrib) 
    AND (TGES_ORDEN.TIP_ORDEN = 2) 
    AND TGES_ORDEN.FEC_ORDEN BETWEEN '$finicioev' AND '$ffinev'
ORDER BY $orden ,TGES_ORDEN.FLG_VIGENCIA desc ";


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
->setCellValue('C1', utf8_encode('A�O'))
->setCellValue('D1', 'N.ORDEN')
->setCellValue('E1', 'NOMBRE')
->setCellValue('F1', 'A.PATERNO')
->setCellValue('G1', 'A.MATERNO')
->setCellValue('H1', 'RUT')
->setCellValue('I1', 'FECHA')
->setCellValue('J1', 'VIGENCIA')
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
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

// Renombrar Hoja 
$objPHPExcel->getActiveSheet()->setTitle('Ordenes'); 

 
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero. 
$objPHPExcel->setActiveSheetIndex(0); 
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel. 
$archivo ="Ordenes ". date("d-m-Y"). ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header("Content-Disposition: attachment; filename=\"$archivo\"");
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output'); 


oci_free_statement($stid);
oci_close($conn);

exit; 
?>