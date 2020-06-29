<html>
 <title>Ingreso entre fechas </title>
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
    <a style="color:#d9534f" class="center navbar-text navbar-center text-light">Ingreso entre Fechas</a>
</nav>
</div>


<div class="container-fluid text-center">
<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$finiciosent=trim(strtoupper($_POST['finiciosent']));
$ffinsent=trim(strtoupper($_POST['ffinsent']));
$glosasent=trim(strtoupper($_POST['glosasent']));
$ctribsent=trim(strtoupper($_POST['ctribsent']));


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
 WHERE (TATP_CAUSA.COD_TRIBUNAL = $ctribsent)
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
print "<table  class='table table-striped table-hover border='1'>\n";
    echo "<tr>\n";
	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."GLOSA DELITO"."<strong></td>\n";
   	     echo "    <td><strong>" ."FECHA INGRESO"."<strong></td>\n";
	echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . utf8_encode(($item)) . "</td>\n";
		
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