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
$finicioev=trim(strtoupper($_GET['finicioev']));
$ffinev=trim(strtoupper($_GET['ffinev']));
$tipoev=trim(strtoupper($_GET['tipoev']));
$glosaev=trim(strtoupper($_GET['glosaev']));
$tipocausa=trim(strtoupper($_GET['tipocausa']));
$ctrib=trim(strtoupper($_GET['ctrib']));

$querypass= "SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC, TATP_CAUSA.IDF_ROLINTERNO AS RIT, 
       TATP_CAUSA.FEC_ERA AS AÑO,
       TO_CHAR(TATP_CAUSA.FEC_INGRESO, 'DD/MM/YYYY') AS FechaIngresoCausa, 
	   TG_TIPEVENTO.GLS_TIPEVENTO AS TIPO_TRAMITE, 
       TGES_EVENTO.GLS_OBSERVACION AS Evento, 
       TO_CHAR(TGES_EVENTO.FEC_EVENTO, 'DD/MM/YYYY') AS FechaEvento, 
       TO_CHAR(TGES_EVENTO.FEC_FIRMA, 'DD/MM/YYYY') AS FechaFirma,
	   TO_CHAR(TGES_EVENTO.FEC_DIGITACION, 'DD/MM/YYYY') AS FechaDigitacion, 
       TG_ESTEVENTO.GLS_ESTEVENTO AS EstadoEvento, 
       TGES_EVENTO.IDF_USUARIODIGITA AS Digitador,
       TGES_EVENTO.IDF_USUARIO AS Firmante,
	   TATP_CAUSA.TIP_CAUSAREF
  FROM JUDPENAL.TATP_CAUSA, 
       JUDPENAL.TG_TIPEVENTO, 
       JUDPENAL.TGES_EVENTO, 
       JUDPENAL.TG_ESTEVENTO 
 WHERE TATP_CAUSA.COD_TRIBUNAL = $ctrib
   AND TATP_CAUSA.IDF_ROLINTERNO > 0 
   AND TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA 
   AND TGES_EVENTO.TIP_EVENTO = TG_TIPEVENTO.TIP_EVENTO 
   AND TGES_EVENTO.EST_EVENTO = TG_ESTEVENTO.EST_EVENTO 
   AND TATP_CAUSA.TIP_CAUSAREF  IN ($tipocausa) 
   AND TGES_EVENTO.TIP_EVENTO IN ($tipoev) 
   AND TGES_EVENTO.FEC_EVENTO >= TO_DATE('$finicioev', 'DD/MM/YYYY') 
   AND TGES_EVENTO.FEC_EVENTO < TO_DATE('$ffinev', 'DD/MM/YYYY') + 1
   AND upper(TGES_EVENTO.GLS_OBSERVACION) LIKE '%$glosaev%'
 ORDER BY JUDPENAL.TGES_EVENTO.FEC_EVENTO DESC";




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
->setCellValue('D1', 'F.ING.CAUSA')
->setCellValue('E1', 'TIPO')
->setCellValue('F1', 'GLOSA')
->setCellValue('G1', 'FECHA DIG')
->setCellValue('H1', 'FECHA FIRMA')
->setCellValue('I1', 'FECHA DIG. REAL')
->setCellValue('J1', 'ESTADO')
->setCellValue('K1', 'CTA. DIG')
->setCellValue('L1', 'CTA. FIRMA')
->setCellValue('M1', 'TIPO CAUSA')
;

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
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);

// Renombrar Hoja 
$objPHPExcel->getActiveSheet()->setTitle('Evento'); 

 
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero. 
$objPHPExcel->setActiveSheetIndex(0); 
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel. 
$archivo ="Eventos ". date("d-m-Y"). ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header("Content-Disposition: attachment; filename=\"$archivo\"");
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output'); 


oci_free_statement($stid);
oci_close($conn);

exit; 
?>