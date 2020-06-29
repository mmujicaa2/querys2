<html>
<body class="CENTRO">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <span class="titulo">****ESTADO CUSTODIA ENTRE FECHAS**** </BR>
  </BR>
 </span>
 <p><strong><a href="xlsespecie.php?finicioesp=<?php echo trim(strtoupper($_POST['finicioesp']));?>
&ffinesp=<?php echo trim(strtoupper($_POST['ffinesp']));?>
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
$finicioesp=trim(strtoupper($_POST['finicioesp']));
$ffinesp=trim(strtoupper($_POST['ffinesp']));

$querypass="SELECT /*+ ORDERED */ TCUS_REPMOVESP.IDF_ROLUNICO, 
       TATP_CAUSA.IDF_ROLINTERNO, 
       TATP_CAUSA.FEC_ERA, 
       TCUS_REPMOVESP.GLS_ESPECIE, 
       TCUS_REPMOVESP.NUM_CANTIDAD,
	   TO_CHAR(TCUS_REPMOVESP.FEC_ASIGNADA, 'dd/mm/yyyy hh24:mi:ss'),
   	   TO_CHAR(TCUS_REPMOVESP.FEC_SISTEMA, 'dd/mm/yyyy hh24:mi:ss'),
       TCUS_REPMOVESP.GLS_TIPMOVIMIENTO, 
       TCUS_REPMOVESP.IDF_CODESPECIE,
	   TCUS_REPMOVESP.CRR_MOVIMIENTO,
   	   TCUS_REPMOVESP.CRR_MOVIMIENTOSAL
	   
  FROM JUDPENAL.TCUS_REPMOVESP TCUS_REPMOVESP, 
       JUDPENAL.TATP_CAUSA TATP_CAUSA 
 WHERE TATP_CAUSA.COD_TRIBUNAL = 953 
   AND TCUS_REPMOVESP.FEC_ASIGNADA BETWEEN TO_DATE('$finicioesp 00:00:00', 'dd/mm/yyyy hh24:mi:ss') AND TO_DATE('$ffinesp 23:59:59', 'dd/mm/yyyy hh24:mi:ss')
   AND TCUS_REPMOVESP.IDF_ROLUNICO = TATP_CAUSA.IDF_ROLUNICO 
   AND TCUS_REPMOVESP.IDF_ROLINTERNO = TATP_CAUSA.IDF_ROLINTERNO
   ORDER BY TCUS_REPMOVESP.FEC_ASIGNADA
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
	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
 	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
  	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."ESPECIE"."<strong></td>\n";
		 echo "    <td><strong>" ."CANTIDAD"."<strong></td>\n";
   	     echo "    <td><strong>" ."F.INGRESO"."<strong></td>\n";
   	     echo "    <td><strong>" ."F.EGRESO"."<strong></td>\n";
   	     echo "    <td><strong>" ."MOVIMIENTO"."<strong></td>\n";
		 echo "    <td><strong>" ."COD.ESPECIE"."<strong></td>\n";
 		 echo "    <td><strong>" ."CRR MOV."."<strong></td>\n";
 		 echo "    <td><strong>" ."CRR MOV. SAL."."<strong></td>\n";
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
