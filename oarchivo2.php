<html>
<body >
<head>
  <title>Hitos entre fechas</title>
<meta http-equiv="Content-Type" content="text/html"; charset="UTF-8">
   <!-- Jquery --> 
  <script src="js/jquery.min.js"></script>

  <!-- Viewport --> 
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="js/bootstrap.min.css">
  <script src="js/bootstrap.min.js"></script>
  <link href="js/font-awesome.min.css" rel="stylesheet">

  <!--Estilos CSS-->
  <link rel="stylesheet" href="css/estilo.css">

  <!--Scripts-->

</head>
<body>
  

 <div class="container-fluid text-center">
    <nav class="navbar navbar-nav  navbar-expand-lg bg-secondary">
    <a style="color:#d9534f" class="center navbar-text navbar-center text-light">Hitos entre fechas</a>
</nav>
</div>


<div class="container text-center">
 <p><strong><a href="xlshito.php?finicio=<?php echo trim(strtoupper($_POST['finicio']));?>
&ffin=<?php echo trim(strtoupper($_POST['ffin']));?>
&ctrib2=<?php echo trim(strtoupper($_POST['ctrib2']));?>
&rit2=<?php echo trim(strtoupper($_POST['rit2']));?>
&anio2=<?php echo trim(strtoupper($_POST['anio2']));?>
&hito=<?php echo trim(strtoupper($_POST['hito']));?>">Exportar a excel</a></strong></p>

</div>

<div class="container-fluid text-center">
<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod','AL32UTF8');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finicio=trim(strtoupper($_POST['finicio']));
$ffin=trim(strtoupper($_POST['ffin']));
$hito=trim(strtoupper($_POST['hito']));
$ctrib2=trim(strtoupper($_POST['ctrib2']));
$rit2=trim(strtoupper($_POST['rit2']));
$anio2=trim(strtoupper($_POST['anio2']));

$querypass= "SELECT TATP_CAUSA.IDF_ROLUNICO AS RUC,
	   TATP_CAUSA.IDF_ROLINTERNO AS RIT , 
       TATP_CAUSA.FEC_ERA AS AñO, 
       TRIM(TGES_EVENTO.GLS_OBSERVACION),
       TO_CHAR(TATP_CAUSA.FEC_INGRESO, 'DD/MM/YYYY') AS FechaIngreso,
       TO_CHAR(TGES_EVENTO.FEC_EVENTO, 'DD/MM/YYYY') AS FechaEvento,
	   TGES_EVENTO.EST_EVENTO,
	   TG_HITOACT.COD_HITOACT,
	   TG_HITOACT.GLS_DESPLIEGUE
  FROM    (   (   JUDPENAL.TGES_EVENTO TGES_EVENTO
               INNER JOIN
                  JUDPENAL.TATP_CAUSA TATP_CAUSA
               ON (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA))
           INNER JOIN
              JUDPENAL.TGES_HITO TGES_HITO
           ON (TGES_HITO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA)
              AND (TGES_HITO.CRR_EVENTO = TGES_EVENTO.CRR_IDEVENTO))
       INNER JOIN
          JUDPENAL.TG_HITOACT TG_HITOACT
       ON (TG_HITOACT.COD_HITOACT = TGES_HITO.COD_HITOACT)
 WHERE (TATP_CAUSA.COD_TRIBUNAL = '$ctrib2')
	AND TATP_CAUSA.TIP_CAUSAREF=1
       AND (TATP_CAUSA.IDF_ROLINTERNO like '$rit2')
       AND (TATP_CAUSA.FEC_ERA like '$anio2')
       AND (TGES_HITO.COD_HITOACT LIKE '$hito' )     
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
print "<table class='table table-striped table-hover' >\n";echo "<tr>\n";
	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."Detalle"."<strong></td>\n";
		 echo "    <td><strong>" ."Fecha ingreso"."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha evento"."<strong></td>\n";
		 echo "    <td><strong>" ."Estado evento"."<strong></td>\n";
		 echo "    <td><strong>" ."Cod. Hito"."<strong></td>\n";
		 echo "    <td><strong>" ."Glosa hito"."<strong></td>\n";
   	    
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
</div>

<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>

</body>
</html>