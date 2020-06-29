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
$finicio=trim(strtoupper($_GET['finicio']));
$ffin=trim(strtoupper($_GET['ffin']));
$hito=trim(strtoupper($_GET['hito']));
$ctrib2=trim(strtoupper($_GET['ctrib2']));
$rit2=trim(strtoupper($_GET['rit2']));
$anio2=trim(strtoupper($_GET['anio2']));



$querypass= "SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC,
	   TATP_CAUSA.IDF_ROLINTERNO AS RIT , 
       TATP_CAUSA.FEC_ERA AS AÑO, 
       TRIM(TGES_EVENTO.GLS_OBSERVACION),
       TO_CHAR(TATP_CAUSA.FEC_INGRESO, 'DD/MM/YYYY') AS FechaIngreso,
       TO_CHAR(TGES_EVENTO.FEC_EVENTO, 'DD/MM/YYYY') AS FechaEvento,
	   TGES_EVENTO.EST_EVENTO,
	   TG_HITOACT.COD_HITOACT,
	   TG_HITOACT.GLS_DESPLIEGUE
  FROM    (   (   JUDPENAL.TGES_EVENTO TGES_EVENTO
               INNER JOIN
                  JUDPENAL.TATP_CAUSA TATP_CAUSA
               ON (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA))
           INNER JOIN
              JUDPENAL.TGES_HITO TGES_HITO
           ON (TGES_HITO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA)
              AND (TGES_HITO.CRR_EVENTO = TGES_EVENTO.CRR_IDEVENTO))
       INNER JOIN
          JUDPENAL.TG_HITOACT TG_HITOACT
       ON (TG_HITOACT.COD_HITOACT = TGES_HITO.COD_HITOACT)
 WHERE (TATP_CAUSA.COD_TRIBUNAL = '$ctrib2')
	AND TATP_CAUSA.TIP_CAUSAREF=1
       AND (TATP_CAUSA.IDF_ROLINTERNO like '$rit2')
       AND (TATP_CAUSA.FEC_ERA like '$anio2')
       AND (TGES_HITO.COD_HITOACT LIKE '$hito' )     
       AND (TGES_EVENTO.FEC_EVENTO BETWEEN TO_DATE('$finicio 00:00:00', 'dd/mm/yyyy hh24:mi:ss') AND TO_DATE('$ffin 23:59:59', 'dd/mm/yyyy hh24:mi:ss'))
ORDER BY TGES_EVENTO.FEC_EVENTO";

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
->setCellValue('D1', 'GLOSA')
->setCellValue('E1', 'FECHA INGRESO')
->setCellValue('F1', 'FECHA EVENTO')
->setCellValue('G1', 'ESTADO EVENTO')
->setCellValue('H1', 'COD.HITO')
->setCellValue('I1', 'GLOSA HITO');

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
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

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