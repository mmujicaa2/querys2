<html>
<body class="CENTRO">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <span class="titulo">****PARTICIPANTES CON DIRECCIONES POR RIT**** </BR>
  </BR>
 </span>
 <p><strong><a href="xlsparticipantes.php?ritpart=<?php echo trim(strtoupper($_POST['ritpart']));?>
&anopart=<?php echo trim(strtoupper($_POST['anopart']));?>
">Exportar a excel</a></strong></p>
</body>
</html>

<style type="text/css">
body {
	background-color: #CF9;
}
.centro {
	text-align: center;
}
.CENTRO {
	text-align: center;
}
.titulo {
	font-weight: bold;
}
</style>
<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$ritpart=trim(strtoupper($_POST['ritpart']));
$anopart=trim(strtoupper($_POST['anopart']));

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






//echo $querypass;
//echo $finicioesp;
//echo $ffinesp;
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
print "<table border='1'>\n";echo "<tr>\n";
 	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
  	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."NOMBRES"."<strong></td>\n";
		 echo "    <td><strong>" ."A.PATERNO"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.MATERNO"."<strong></td>\n";
   	     echo "    <td><strong>" ."CALLE"."<strong></td>\n";
   	     echo "    <td><strong>" ."N°"."<strong></td>\n";
    echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	
    print "<tr>\n";
    foreach ($row as $item) {
        //print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
		print "    <td>" . ($item) . "</td>\n";
		
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
