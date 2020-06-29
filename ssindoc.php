<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
.CENTRO {
	font-weight: bold;
}
</style>
<body class="CENTRO">
****SOLICITUDES SIN DOC NI WORD NI PDF**** </BR>
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
$finiciossdoc=trim(strtoupper($_POST['finiciossdoc']));
$ffinssdoc=trim(strtoupper($_POST['ffinssdoc']));

$querypass= 
"SELECT distinct TATP_CAUSA.IDF_ROLINTERNO, 
       TATP_CAUSA.FEC_ERA, 
       TGES_EVENTO.CRR_IDEVENTO, 
       TGES_EVENTO.FEC_EVENTO, 
       TGES_EVENTO.GLS_OBSERVACION 
  FROM JUDPENAL.TDOC_PDFDOC TDOC_PDFDOC 
       INNER JOIN JUDPENAL.TDOC_PDF TDOC_PDF 
          ON (TDOC_PDFDOC.CRR_PDF = TDOC_PDF.CRR_IDPDF) 
       CROSS JOIN (JUDPENAL.TGES_EVENTO TGES_EVENTO 
           INNER JOIN JUDPENAL.TG_TIPEVENTO TG_TIPEVENTO 
              ON (TGES_EVENTO.TIP_EVENTO = TG_TIPEVENTO.TIP_EVENTO)) 
       INNER JOIN JUDPENAL.TATP_CAUSA TATP_CAUSA 
          ON (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA) 
 WHERE (TATP_CAUSA.COD_TRIBUNAL = 953) 
   AND (TG_TIPEVENTO.TIP_EVENTO = 3) 
   AND (TGES_EVENTO.FEC_EVENTO BETWEEN TO_DATE('$finiciossdoc 00:00:00', 'dd/mm/yyyy hh24:mi:ss') AND TO_DATE('$ffinssdoc 00:00:00', 'dd/mm/yyyy hh24:mi:ss')) 
   AND (TGES_EVENTO.CRR_DOCUMENTO = 0)
   AND (TATP_CAUSA.TIP_CAUSAREF = 1)
   AND (TGES_EVENTO.CRR_IDEVENTO NOT IN (SELECT TDOC_PDF.CRR_REFERENCIAPDF 
                                           FROM TDOC_PDF))
   ORDER BY TGES_EVENTO.FEC_EVENTO DESC
";



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
        print "    <td>" . ($item ) . "</td>\n";
		
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
