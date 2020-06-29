<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ordenes</title>
<style type="text/css">
.titulo {
}
</style>
</head>
<p><strong>****CAUSAS POR TRIBUNAL****</strong></p>
<p><strong><a href="xlsparticipante.php?nombreimp=<?php echo trim(strtoupper($_POST['nombreimp']));?>
&apaternoimp=<?php echo trim(strtoupper($_POST['apaternoimp']));?>
&amaternoimp=<?php echo trim(strtoupper($_POST['amaternoimp']));?>
&codtrib=<?php echo trim(strtoupper($_POST['codtrib']));?>
&tipo=<?php echo ($_POST['tipo']);?>">Exportar a excel</a></strong></p>
</body>
</html>


<?php

//var_dump($_POST['nombreimp']);

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod','AL32UTF8');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$nombreimp=trim(strtoupper($_POST['nombreimp']));
$apaterno=trim(strtoupper($_POST['apaternoimp']));
$amaterno=trim(strtoupper($_POST['amaternoimp']));
$codtrib=trim(strtoupper($_POST['codtrib']));
$tipo=$_POST['tipo'];


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
   AND TATP_CAUSA.COD_TRIBUNAL LIKE '$codtrib%' 
   AND TATP_PERSONA.IDF_NOMBRES LIKE '$nombreimp%' 
   AND TATP_PERSONA.IDF_PATERNO LIKE '$apaterno%' 
   AND TATP_PERSONA.IDF_MATERNO LIKE '$amaterno%'
   AND TATP_PARTICIPANTE.TIP_PARTICIPANTE=$tipo
   AND TATP_CAUSA.TIP_CAUSAREF=1
   AND TATP_DELITO.CRR_CAUSA = TATP_PARTICIPANTE.CRR_CAUSA 
   ORDER BY NOMBRES ASC,PATERNO ASC,MATERNO ASC";

 

echo $querypass;

$stid = oci_parse($conn,$querypass);


if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentiaties($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en query";
	
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error al ejecutar query";
}

// Fetch the results of the query
print "<table border='1'>\n";
	 echo "<tr>\n";
 	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."Tribunal"."<strong></td>\n";
   	     echo "    <td><strong>" ."Cod.Trib."."<strong></td>\n";
   	     echo "    <td><strong>" ."Nombres"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Paterno"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Materno"."<strong></td>\n";
		 echo "    <td><strong>" ."RUT"."<strong></td>\n";
   	     echo "    <td><strong>" ."Delito"."<strong></td>\n";
    echo "</tr>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

	
    echo "<tr>\n";
    foreach ($row as $item) {
        //echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "nowrap") . "</td>\n";
	     echo "    <td>" . ($item )."</td>\n";
		
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
