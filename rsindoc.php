<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
.CENTRO {
	font-weight: bold;
}
</style>
<body class="CENTRO">
****RESOLUCIONES SIN DOC**** </BR>
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

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finiciorsdoc=trim(strtoupper($_POST['finiciorsdoc']));
$ffinrsdoc=trim(strtoupper($_POST['ffinrsdoc']));

$querypass= "SELECT TATP_CAUSA.IDF_ROLINTERNO,
       TATP_CAUSA.FEC_ERA,
       TGES_EVENTO.CRR_IDEVENTO,
       TGES_EVENTO.FEC_EVENTO,
      TGES_EVENTO.GLS_OBSERVACION
      
  FROM    (   JUDPENAL.TGES_EVENTO TGES_EVENTO
           INNER JOIN
              JUDPENAL.TG_TIPEVENTO TG_TIPEVENTO
           ON (TGES_EVENTO.TIP_EVENTO = TG_TIPEVENTO.TIP_EVENTO))
       INNER JOIN
          JUDPENAL.TATP_CAUSA TATP_CAUSA
       ON (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA)
 WHERE (TATP_CAUSA.COD_TRIBUNAL = 953) AND (TG_TIPEVENTO.TIP_EVENTO = 2)
       AND (TGES_EVENTO.FEC_EVENTO BETWEEN TO_DATE ('$finiciorsdoc 00:00:00',
                                                    'dd/mm/yyyy hh24:mi:ss')
                                       AND TO_DATE ('$ffinrsdoc 00:00:00',
                                                    'dd/mm/yyyy hh24:mi:ss'))
       AND (TGES_EVENTO.CRR_DOCUMENTO = 0)
       AND (TGES_EVENTO.EST_EVENTO = 8)
       AND (TATP_CAUSA.TIP_CAUSAREF = 1)
ORDER BY TATP_CAUSA.FEC_ERA DESC, TATP_CAUSA.IDF_ROLINTERNO DESC";

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
  	     echo "    <td><strong>" ."CRR_evento"."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha Evento"."<strong></td>\n";
   	     echo "    <td><strong>" ."Glosa"."<strong></td>\n";
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
