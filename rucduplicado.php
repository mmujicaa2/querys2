<html>

<head>
  <title>Búsqueda de RIT por RUC</title>
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



<div class="container-fluid text-center">
    <nav class="navbar navbar-nav  navbar-expand-lg bg-secondary">
    <a style="color:#d9534f" class="center navbar-text navbar-center text-light">RIT por RUC</a>
</nav>
</div>


<div class="container-fluid text-center">

<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Error en conexion";
}
$rucduplicado=trim(strtoupper($_POST['rucduplicado']));
$ctrib=trim(strtoupper($_POST['ctrib']));


{
$querypass= 
	"select tatp_causa.idf_rolunico, tatp_causa.idf_rolinterno, tatp_causa.fec_era, tatp_causa.cod_tribunal
	from tatp_causa
	where tatp_causa.idf_rolunico like '%$rucduplicado%'
	AND TATP_CAUSA.TIP_CAUSAREF = 1
	and tatp_causa.cod_tribunal LIKE '%$ctrib%'
	";
} 



//echo $querypass;

$stid = oci_parse($conn,$querypass);


if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentiaties($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Error en query";
	
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Error al ejecutar query";
}

// Fetch the results of the query
print "<table  class='table table-striped table-hover border='1'>\n";
	 echo "<tr>\n";
  	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
  	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
  	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."Código de Tribunal"."<strong></td>\n";
 	     
    echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

    echo "<tr>\n";
    foreach ($row as $item) {
        //echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "nowrap") . "</td>\n";
	     echo "    <td>" . utf8_encode(($item ))."</td>\n";
		
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
</div>

<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>

</head>
<body>