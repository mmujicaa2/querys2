<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
.CENTRO {
	font-weight: bold;
}
</style>
<body class="CENTRO">
****INGRESO ENTRE FECHAS X GLOSA DELITO**** </BR>
 </BR>
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
</style>
<?php

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finiciosent=trim(strtoupper($_POST['finiciosent']));
$ffinsent=trim(strtoupper($_POST['ffinsent']));
$glosasent=trim(strtoupper($_POST['glosasent']));


$querypass= "SELECT DISTINCT TATP_CAUSA.IDF_ROLUNICO,TATP_CAUSA.IDF_ROLINTERNO,
       TATP_CAUSA.FEC_ERA,
       TATP_MATERIA.GLS_MATERIA,
       TATP_CAUSA.FEC_INGRESO
  FROM    (   JUDPENAL.TATP_DELITO TATP_DELITO
           INNER JOIN
              JUDPENAL.TATP_MATERIA TATP_MATERIA
           ON (TATP_DELITO.COD_MATERIA = TATP_MATERIA.COD_MATERIA))
       INNER JOIN
          JUDPENAL.TATP_CAUSA TATP_CAUSA
       ON (TATP_DELITO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA)
 WHERE (TATP_CAUSA.COD_TRIBUNAL = 953)
       AND (TATP_CAUSA.TIP_CAUSAREF = 1)
       AND (UPPER(TATP_MATERIA.GLS_MATERIA) LIKE '%$glosasent%')
       AND (TATP_CAUSA.FEC_INGRESO BETWEEN TO_DATE ('$finiciosent 00:00:00',
                                                    'dd/mm/yyyy hh24:mi:ss')
                                       AND TO_DATE ('$ffinsent 00:00:00',
                                                    'dd/mm/yyyy hh24:mi:ss'))
ORDER BY TATP_CAUSA.FEC_ERA DESC, TATP_CAUSA.IDF_ROLINTERNO ASC";


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
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."GLOSA DELITO"."<strong></td>\n";
   	     echo "    <td><strong>" ."FECHA INGRESO"."<strong></td>\n";
	echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item) . "</td>\n";
		
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
