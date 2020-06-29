<?php 
/** Incluir la libreria PHPExcel */
require_once("../PHPExcel/Classes/PHPExcel.php");
//require_once("../PHPExcel/Classes/PHPExcel/Writer/Excel2007.php");
 
 
// Crea un nuevo objeto PHPExcel 
$objPHPExcel = new PHPExcel(); 
 
 
// Establecer propiedades 
$objPHPExcel->getProperties() 
->setCreator("Cattivo") 
->setLastModifiedBy("Cattivo") 
->setTitle("Documento Excel de Prueba") 
->setSubject("Documento Excel de Prueba") 
->setDescription("Demostracion sobre como crear archivos de Excel desde PHP.") 
->setKeywords("Excel Office 2007 openxml php") 
->setCategory("Pruebas de Excel"); 
 
 
// Agregar Informacion 
$objPHPExcel->setActiveSheetIndex(0) 
->setCellValue('A1', 'Valor 1') 
->setCellValue('B1', 'Valor 2') 
->setCellValue('C1', 'Total') 
->setCellValue('A2', '10') 
->setCellValue('C2', '=sum(A2:B2)'); 
 
 
$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$nombreimp=trim(strtoupper($_GET['nombreimp']));
$apaternoimp=trim(strtoupper($_GET['apaternoimp']));
$amaternoimp=trim(strtoupper($_GET['amaternoimp']));
$tipo=$_GET['tipo'];

$querypass= 
"SELECT TATP_CAUSA.IDF_ROLINTERNO AS RIT,
       TATP_CAUSA.FEC_ERA AS ANO,
       TG_TRIBUNAL.GLS_TRIBUNAL AS TRIBUNAL,
       TG_TRIBUNAL.COD_TRIBUNAL AS COD_TRIB,
       TATP_PERSONA.IDF_NOMBRES AS NOMBRES,
       TATP_PERSONA.IDF_PATERNO AS APATERNO,
       TATP_PERSONA.IDF_MATERNO AS AMATERNO     
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
	   ORDER BY TG_TRIBUNAL.COD_TRIBUNAL DESC";

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




while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		



    foreach ($row as $item) {
		
		//$objPHPExcel->getActiveSheet()->SetCellValue("B".$item["RIT"]);
		//$objPHPExcel->getActiveSheet()->SetCellValue("C".$item["ANO"]);
		//$objPHPExcel->getActiveSheet()->setCellValue("D".$item["TRIBUNAL"]);
		//$objPHPExcel->getActiveSheet()->setCellValue("E".$item["COD_TRIB"]);
		//$objPHPExcel->getActiveSheet()->setCellValue("F".$item["NOMBRES"]); 
		//$objPHPExcel->getActiveSheet()->setCellValue("G".$item["APATERNO"]); 
		//$objPHPExcel->getActiveSheet()->setCellValue("H".$item["AMATERNO"]); 

	
    }
    
}
 
 
 
 
// Renombrar Hoja 
$objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple'); 
 
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero. 
$objPHPExcel->setActiveSheetIndex(0); 
 
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel. 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="pruebaReal.xlsx"'); 
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$objWriter->save('php://output'); 


oci_free_statement($stid);
oci_close($conn);

exit; 
?>