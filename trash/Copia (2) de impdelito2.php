<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ordenes</title>
<style type="text/css">
.titulo {
}
</style>
</head>
<p><strong>****CAUSAS POR TRIBUNAL****</strong></p>
<p><strong><a href="xlsparticipante2.php?rutimp=<?php echo trim(strtoupper($_POST['rutimp']));?>
&tipo2=<?php echo ($_POST['tipo2']);?>">Exportar a excel</a></strong></p>
</body>
</html>

<style type="text/css">
body {
	background-color: #CF9;
	text-align: center;
}
.centro {
	text-align: center;
}
.CENTRO {
	text-align: center;
}
.titulo {
	font-weight: bold;
	text-align: center;
}
.centrado {
	text-align: center;
}
</style>
<?php

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$rutimp=trim(strtoupper($_POST['rutimp']));
$tipo2=$_POST['tipo2'];

$querypass="SELECT /*+ ORDERED */ TATP_CAUSA.IDF_ROLUNICO AS RUC, 
       TATP_CAUSA.IDF_ROLINTERNO AS RIT, 
       TATP_CAUSA.FEC_ERA AS ANO, 
       TG_TRIBUNAL.GLS_TRIBUNAL AS TRIBUNAL, 
       TG_TRIBUNAL.COD_TRIBUNAL AS COD_TRIB, 
       TATP_PERSONA.IDF_NOMBRES AS NOMBRES, 
       TATP_PERSONA.IDF_PATERNO AS PATERNO, 
       TATP_PERSONA.IDF_MATERNO AS MATERNO, 
       TATP_PERSONA.NUM_DOCID AS RUT, 
       TATP_MATERIA.GLS_MATERIA 
  FROM JUDPENAL.TATP_PARTICIPANTE, 
       JUDPENAL.TATP_CAUSA, 
       JUDPENAL.TATP_DELITO, 
       JUDPENAL.TG_TRIBUNAL, 
       JUDPENAL.TATP_PERSONA, 
       JUDPENAL.TATP_MATERIA 
 WHERE TATP_CAUSA.CRR_IDCAUSA = TATP_PARTICIPANTE.CRR_CAUSA + 0 
   AND TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA + 0 
   AND TG_TRIBUNAL.COD_TRIBUNAL = TATP_CAUSA.COD_TRIBUNAL + 0 
   AND TATP_MATERIA.COD_MATERIA = TATP_DELITO.COD_MATERIA + 0 
   AND TATP_DELITO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA + 0 
   AND TATP_PERSONA.NUM_DOCID like '%$rutimp%'
   AND TATP_PARTICIPANTE.TIP_PARTICIPANTE = NVL($tipo2, UID) 
   AND TATP_CAUSA.TIP_CAUSAREF + 0 = 1 
   AND TATP_DELITO.CRR_CAUSA = TATP_PARTICIPANTE.CRR_CAUSA + 0 
 ORDER BY NOMBRES ASC, 
          PATERNO ASC, 
          MATERNO ASC";





//echo $querypass;

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
