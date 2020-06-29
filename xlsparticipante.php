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
$nombreimp=trim(strtoupper($_GET['nombreimp']));
$apaternoimp=trim(strtoupper($_GET['apaternoimp']));
$amaternoimp=trim(strtoupper($_GET['amaternoimp']));
$codtrib=trim(strtoupper($_GET['codtrib']));
$tipo=$_GET['tipo'];

$querypass= 
$querypass="SELECT   /*+ USE_HASH(TATP_DELITO,TATP_MATERIA,TG_TRIBUNAL) */ 
	   TATP_CAUSA.IDF_ROLUNICO AS RUC,
	   TATP_CAUSA.IDF_ROLINTERNO AS RIT, 
       TATP_CAUSA.FEC_ERA AS ANO, 
       TG_TRIBUNAL.GLS_TRIBUNAL AS TRIBUNAL, 
       TG_TRIBUNAL.COD_TRIBUNAL AS COD_TRIB, 
       TATP_PERSONA.IDF_NOMBRES AS NOMBRES, 
       TATP_PERSONA.IDF_PATERNO AS PATERNO, 
       TATP_PERSONA.IDF_MATERNO AS MATERNO,
	   TATP_PERSONA.NUM_DOCID AS RUT,
       TATP_MATERIA.GLS_MATERIA
   
  FROM JUDPENAL.TATP_CAUSA, 
       JUDPENAL.TATP_PERSONA, 
       JUDPENAL.TATP_PARTICIPANTE, 
       JUDPENAL.TG_TRIBUNAL, 
       JUDPENAL.TATP_MATERIA, 
       JUDPENAL.TATP_DELITO 
	   
 WHERE TATP_PARTICIPANTE.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA 
   AND TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA 
   AND TATP_CAUSA.COD_TRIBUNAL = TG_TRIBUNAL.COD_TRIBUNAL 
   AND TATP_DELITO.COD_MATERIA = TATP_MATERIA.COD_MATERIA 
   AND TATP_CAUSA.CRR_IDCAUSA = TATP_DELITO.CRR_CAUSA 
   AND TATP_CAUSA.COD_TRIBUNAL like '$codtrib%' 
   AND TATP_PERSONA.IDF_NOMBRES LIKE '$nombreimp%' 
   AND TATP_PERSONA.IDF_PATERNO LIKE '$apaternoimp%' 
   AND TATP_PERSONA.IDF_MATERNO LIKE '$amaternoimp%'
   AND TATP_PARTICIPANTE.TIP_PARTICIPANTE=$tipo
   AND TATP_CAUSA.TIP_CAUSAREF=1
   AND TATP_DELITO.CRR_CAUSA = TATP_PARTICIPANTE.CRR_CAUSA 
   ORDER BY NOMBRES ASC,PATERNO ASC,MATERNO ASC";



/*"SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC,
TATP_CAUSA.IDF_ROLINTERNO AS RIT,
       TATP_CAUSA.FEC_ERA AS ANO,
       TG_TRIBUNAL.GLS_TRIBUNAL AS TRIBUNAL,
       TG_TRIBUNAL.COD_TRIBUNAL AS COD_TRIB,
       TATP_PERSONA.IDF_NOMBRES AS NOMBRES,
       TATP_PERSONA.IDF_PATERNO AS APATERNO,
       TATP_PERSONA.IDF_MATERNO AS AMATERNO,
       TATP_PERSONA.NUM_DOCID AS RUT
	  FROM JUDPENAL.TATP_CAUSA,JUDPENAL.TATP_PERSONA,JUDPENAL.TATP_PARTICIPANTE,JUDPENAL.TG_TRIBUNAL
		WHERE  
       (TATP_CAUSA.CRR_IDCAUSA=TATP_PARTICIPANTE.CRR_CAUSA)
       AND (TATP_PARTICIPANTE.CRR_PERSONA = TATP_PERSONA.CRR_LITIGANTE_ID)
       AND TATP_PERSONA.IDF_NOMBRES LIKE '$nombreimp%' 
       AND TATP_PERSONA.IDF_PATERNO LIKE '$apaternoimp%' 
       AND TATP_PERSONA.IDF_MATERNO LIKE '$amaternoimp%' 
       AND (TG_TRIBUNAL.COD_TRIBUNAL=TATP_CAUSA.COD_TRIBUNAL)
       AND TATP_PARTICIPANTE.TIP_PARTICIPANTE=$tipo
       AND TATP_CAUSA.TIP_CAUSAREF=1
	   ORDER BY NOMBRES ASC,APATERNO ASC,AMATERNO ASC";*/

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
->setCellValue('D1', 'TRIBUNAL')
->setCellValue('E1', 'COD_TRIB')
->setCellValue('F1', 'NOMBRES')
->setCellValue('G1', 'A.PATERNO')
->setCellValue('H1', 'A.MATERNO')
->setCellValue('I1', 'RUT')
->setCellValue('J1', 'DELITO');
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
$objPHPExcel->getActiveSheet()->setTitle('Participante'); 

 
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero. 
$objPHPExcel->setActiveSheetIndex(0); 
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel. 
$archivo ="Participante ". date("d-m-Y"). ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header("Content-Disposition: attachment; filename=\"$archivo\"");
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output'); 


oci_free_statement($stid);
oci_close($conn);

exit; 
?>