<html>
<body class="CENTRO">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <span class="titulo">****HITO ENTRE FECHAS**** </BR>
 <p><strong><a href="xlshito.php?finicio=<?php echo trim(strtoupper($_POST['finicio']));?>
&ffin=<?php echo trim(strtoupper($_POST['ffin']));?>
&hito=<?php echo trim(strtoupper($_POST['hito']));?>">Exportar a excel</a></strong></p>
  </BR>
 </span>
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

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finicio=trim(strtoupper($_POST['finicio']));
$ffin=trim(strtoupper($_POST['ffin']));
$hito=trim(strtoupper($_POST['hito']));

$querypass= 
"SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC,
	   TATP_CAUSA.IDF_ROLINTERNO AS RIT , 
       TATP_CAUSA.FEC_ERA AS AÑO, 
       TRIM(TGES_EVENTO.GLS_OBSERVACION),
       TO_CHAR(TATP_CAUSA.FEC_INGRESO, 'DD/MM/YYYY') AS FechaIngreso,
       TO_CHAR(TGES_EVENTO.FEC_EVENTO, 'DD/MM/YYYY') AS FechaEvento,
	   TGES_EVENTO.EST_EVENTO
  FROM TATP_CAUSA, 
       TGES_EVENTO, 
       TGES_HITO 
 WHERE TATP_CAUSA.COD_TRIBUNAL = 953
   AND (TATP_CAUSA.TIP_CAUSAREF = 1 ) 
   AND (TGES_HITO.COD_HITOACT = $hito ) 
   AND (TGES_HITO.CRR_EVENTO = TGES_EVENTO.CRR_IDEVENTO) 
   AND (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA) 
   AND (TGES_EVENTO.FEC_EVENTO BETWEEN TO_DATE('$finicio 00:00:00', 'dd/mm/yyyy hh24:mi:ss') AND TO_DATE('$ffin 23:59:59', 'dd/mm/yyyy hh24:mi:ss'))
ORDER BY TGES_EVENTO.FEC_EVENTO";

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
print "<table border='1'>\n";echo "<tr>\n";
	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."Glosa"."<strong></td>\n";
		 echo "    <td><strong>" ."Fecha ingreso"."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha evento"."<strong></td>\n";
		 echo "    <td><strong>" ."Estado evento"."<strong></td>\n";
   	    
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
