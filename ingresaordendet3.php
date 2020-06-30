
<html>
<head>
  <title>Estado de Contra Ordenes de Detención </title>
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
   	 <a style="color:#d9534f" class="center navbar-text navbar-center text-light">Estado de Contra Ordenes de Detención</a>
   </nav>

 </div>

 <div class="container text-center">
	 <p class="text-dark form-group"><strong><a class="container" href="xlscorden.php?finicioev=<?php echo trim(strtoupper($_POST['finicioev']));?>
	&ffinev=<?php echo trim(strtoupper($_POST['ffinev']));?>
	&ctrib=<?php echo trim(strtoupper($_POST['ctrib']));?>
	">Exportar a excel</a></strong></p>
</div>



<?php 

$finicioev=trim(strtoupper($_POST['finicioev']));
$ffinev=trim(strtoupper($_POST['ffinev']));
$ctrib=trim(strtoupper($_POST['ctrib']));

$orden="TATP_PERSONA.IDF_NOMBRES ASC";


$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Error en conexión";
}
$querypass= "SELECT DISTINCT RIT.IDF_ROLUNICO, 
       RIT.IDF_ROLINTERNO, 
       RIT.FEC_ERA, 
       TGES_ORDENROL.IDF_ROLUNICOORDEN, 
       TATP_PERSONA.IDF_NOMBRES, 
       TATP_PERSONA.IDF_PATERNO, 
       TATP_PERSONA.IDF_MATERNO, 
       TATP_PERSONA.NUM_DOCID, 
       TGES_ORDENROL.FEC_SISTEMA,
	   TGES_ORDEN.FLG_VIGENCIA,
       TGES_ORDEN.GLS_OBSERVACION 
  FROM (((JUDPENAL.TGES_ORDEN TGES_ORDEN 
       INNER JOIN JUDPENAL.TATP_PARTICIPANTE TATP_PARTICIPANTE 
          ON (TGES_ORDEN.CRR_PARTICIPANTE = TATP_PARTICIPANTE.CRR_IDPARTICIPANTE)) 
       INNER JOIN JUDPENAL.TGES_ORDENROL TGES_ORDENROL 
          ON (TGES_ORDENROL.CRR_ORDEN = TGES_ORDEN.CRR_IDORDEN)) 
       INNER JOIN JUDPENAL.TATP_PERSONA TATP_PERSONA 
          ON (TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA)) 
       INNER JOIN JUDPENAL.TATP_CAUSA RIT 
          ON (RIT.CRR_IDCAUSA = TATP_PARTICIPANTE.CRR_CAUSA) 
 WHERE (RIT.COD_TRIBUNAL = $ctrib) 
    AND (TGES_ORDEN.TIP_ORDEN = 2) 
    AND TGES_ORDEN.FEC_ORDEN BETWEEN '$finicioev' AND '$ffinev'
ORDER BY $orden ,TGES_ORDEN.FLG_VIGENCIA desc ";


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
	echo "error al ejecutar query";
}

// Fetch the results of the query

print "<table class='table table-striped border='1'>\n";echo "<tr>\n";
 echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."Año"."<strong></td>\n";
  	     echo "    <td><strong>" ."N° Orden"."<strong></td>\n";
  	     echo "    <td><strong>" ."Nombres"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Paterno"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Materno"."<strong></td>\n";
   	     echo "    <td><strong>" ."RUT."."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha"."<strong></td>\n";
   	     echo "    <td><strong>" ."Vigencia"."<strong></td>\n";
   	     echo "    <td><strong>" ."Observaciónes"."<strong></td>\n";

echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$i=0;

    foreach ($row as $item) {
		
		
			print "    <td>" . utf8_encode(($item)) . "</td>\n";
		
		$i++;
    }
    print "</tr>\n";
	 	

}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 


<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>
</body>
</html>


