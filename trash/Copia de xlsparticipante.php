<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php
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
       
  FROM    JUDPENAL.TATP_CAUSA,JUDPENAL.TATP_PERSONA,JUDPENAL.TATP_PARTICIPANTE,JUDPENAL.TG_TRIBUNAL
 WHERE  
        (TATP_CAUSA.CRR_IDCAUSA=TATP_PARTICIPANTE.CRR_CAUSA)
       AND (TATP_PARTICIPANTE.CRR_PERSONA = TATP_PERSONA.CRR_LITIGANTE_ID)
       AND TATP_PERSONA.IDF_NOMBRES LIKE '$nombreimp%' 
       AND TATP_PERSONA.IDF_PATERNO LIKE '$apaternoimp%' 
       AND TATP_PERSONA.IDF_MATERNO LIKE '$amaternoimp%' 
      AND (TG_TRIBUNAL.COD_TRIBUNAL=TATP_CAUSA.COD_TRIBUNAL)
       AND TATP_PARTICIPANTE.TIP_PARTICIPANTE=$tipo
       AND TATP_CAUSA.TIP_CAUSAREF=1
	   ORDER BY TG_TRIBUNAL.COD_TRIBUNAL DESC
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

$XML = "RIT"."\n"."ANO"."TRIBUNAL"."COD_TRIB"."NOMBRES"."APATERNO"."AMATERNO\n";

$file ="Participante_". date("Y-m-d"). ".xls";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

    foreach ($row as $item) {
	//$XML.= $item[RIT]."\n";
    //$XML.= $item[ANO]."\n";
    //$XML.= $item[TRIBUNAL]."\n";
    //$XML.= $item[COD_TRIB]."\n";
    //$XML.= $item[NOMBRES]."\n";
    //$XML.= $item[APATERNO]."\n";
    //$XML.= $item[AMATERNO]."\n";
    }
    
}
header("Content-type: application/ms-excel"); 
//header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$file\"");
header("Content-Transfer-Encoding: binary");
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
    header('Cache-Control: public');
}
echo $XML;
oci_free_statement($stid);
oci_close($conn);
exit;
?> 
<body>
</body>
</html>