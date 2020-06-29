<html>
<body class="CENTRO">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <span class="titulo">****ESTADO EVENTOS**** </BR>
  </BR>
 </span>
 <p><strong><a href="xlsevento.php?finicioev=<?php echo trim(strtoupper($_POST['finicioev']));?>
&ffinev=<?php echo trim(strtoupper($_POST['ffinev']));?>
&tipoev=<?php echo trim(strtoupper($_POST['tipoev']));?>
&glosaev=<?php echo trim(strtoupper($_POST['glosaev']));?>
&tipocausa=<?php echo trim(strtoupper($_POST['tipocausa']));?>
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

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finicioev=trim(strtoupper($_POST['finicioev']));
$ffinev=trim(strtoupper($_POST['ffinev']));
$tipoev=trim(strtoupper($_POST['tipoev']));
$glosaev=trim(strtoupper($_POST['glosaev']));
$tipocausa=trim(strtoupper($_POST['tipocausa']));

$querypass= "SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC, TATP_CAUSA.IDF_ROLINTERNO AS RIT, 
       TATP_CAUSA.FEC_ERA AS AÑO,
	   TATP_CAUSA.FEC_INGRESO AS FECHA_INGRESO_CAUSA,
       TG_TIPEVENTO.GLS_TIPEVENTO AS TIPO_TRAMITE, 
       TGES_EVENTO.GLS_OBSERVACION AS Evento, 
       TO_CHAR(TGES_EVENTO.FEC_EVENTO, 'DD/MM/YYYY') AS FechaEvento, 
       TO_CHAR(TGES_EVENTO.FEC_FIRMA, 'DD/MM/YYYY') AS FechaFirma,
	   TO_CHAR(TGES_EVENTO.FEC_DIGITACION, 'DD/MM/YYYY') AS FechaDigitacion,  
       TG_ESTEVENTO.GLS_ESTEVENTO AS EstadoEvento, 
       TGES_EVENTO.IDF_USUARIODIGITA AS Digitador, 
       TGES_EVENTO.IDF_USUARIO AS Firmante 
  FROM JUDPENAL.TATP_CAUSA, 
       JUDPENAL.TG_TIPEVENTO, 
       JUDPENAL.TGES_EVENTO, 
       JUDPENAL.TG_ESTEVENTO 
 WHERE TATP_CAUSA.COD_TRIBUNAL = 1058
   AND TATP_CAUSA.IDF_ROLINTERNO > 0 
   AND TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA 
   AND TGES_EVENTO.TIP_EVENTO = TG_TIPEVENTO.TIP_EVENTO 
   AND TGES_EVENTO.EST_EVENTO = TG_ESTEVENTO.EST_EVENTO 
   AND TATP_CAUSA.TIP_CAUSAREF IN ($tipocausa) 
   AND TGES_EVENTO.TIP_EVENTO IN ($tipoev) 
   AND TGES_EVENTO.FEC_EVENTO >= TO_DATE('$finicioev', 'DD/MM/YYYY') 
   AND TGES_EVENTO.FEC_EVENTO < TO_DATE('$ffinev', 'DD/MM/YYYY') + 1
   AND UPPER(TGES_EVENTO.GLS_OBSERVACION) LIKE UPPER('%$glosaev%')
 ORDER BY JUDPENAL.TGES_EVENTO.FEC_EVENTO DESC";


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
 	     echo "    <td><strong>" ."Año"."<strong></td>\n";
  	     echo "    <td><strong>" ."F.Ing.Causa"."<strong></td>\n";
  	     echo "    <td><strong>" ."Tipo Evento"."<strong></td>\n";
   	     echo "    <td><strong>" ."Glosa"."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha Dig."."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha Firma."."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha Dig. Real."."<strong></td>\n";
   	     echo "    <td><strong>" ."Estado"."<strong></td>\n";
   	     echo "    <td><strong>" ."Cta. Digitador"."<strong></td>\n";
   	     echo "    <td><strong>" ."Cta. Firmante"."<strong></td>\n";
   	    
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
