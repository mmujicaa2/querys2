<html>

<head>
  <title>Causas por Participante</title>
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
    <a style="color:#d9534f" class="center navbar-text navbar-center text-light">Participantes por RUT</a>
</nav>
</div>



<div class="container text-center">
<p><strong><a href="xlsparticipante2.php?rutimp=<?php echo trim(strtoupper($_POST['rutimp']));?>
&tipo2=<?php echo ($_POST['tipo2']);?>
&codtrib2=<?php echo ($_POST['codtrib2']);?>"
>Exportar a excel</a></strong></p>
</div>

<div class="container-fluid text-center">

<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$rutimp=trim(strtoupper($_POST['rutimp']));
$codtrib2=$_POST['codtrib2'];
$tipo2=$_POST['tipo2'];

$querypass="SELECT /*+ ORDERED */ TATP_CAUSA.IDF_ROLUNICO AS RUC, 
       TATP_CAUSA.IDF_ROLINTERNO AS RIT, 
       TATP_CAUSA.FEC_ERA AS ANO, 
       TG_TRIBUNAL.GLS_TRIBUNAL AS TRIBUNAL, 
       TG_TRIBUNAL.COD_TRIBUNAL AS COD_TRIB, 
       TATP_PERSONA.IDF_NOMBRES AS NOMBRES, 
       TATP_PERSONA.IDF_PATERNO AS PATERNO, 
       TATP_PERSONA.IDF_MATERNO AS MATERNO, 
       TATP_PERSONA.NUM_DOCID AS RUT, 
       TATP_MATERIA.GLS_MATERIA 
  FROM JUDPENAL.TATP_PARTICIPANTE, 
       JUDPENAL.TATP_CAUSA, 
       JUDPENAL.TATP_DELITO, 
       JUDPENAL.TG_TRIBUNAL, 
       JUDPENAL.TATP_PERSONA, 
       JUDPENAL.TATP_MATERIA
	   
 WHERE TATP_CAUSA.CRR_IDCAUSA = TATP_PARTICIPANTE.CRR_CAUSA + 0 
   AND TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA + 0 
   AND TG_TRIBUNAL.COD_TRIBUNAL = TATP_CAUSA.COD_TRIBUNAL + 0 
   AND TATP_MATERIA.COD_MATERIA = TATP_DELITO.COD_MATERIA + 0 
   AND TATP_DELITO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA + 0 
   AND TATP_PERSONA.NUM_DOCID like '%$rutimp%'
   AND TATP_CAUSA.COD_TRIBUNAL LIKE '$codtrib2%' 
   AND TATP_PARTICIPANTE.TIP_PARTICIPANTE = NVL($tipo2, UID) 
   AND TATP_CAUSA.TIP_CAUSAREF + 0 = 1 
   AND TATP_DELITO.CRR_CAUSA = TATP_PARTICIPANTE.CRR_CAUSA + 0 

 ORDER BY NOMBRES ASC, 
          PATERNO ASC, 
          MATERNO ASC";





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
print "<table class='table table-striped table-hover border='1'>\n";
	 echo "<tr>\n";
 	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
 	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."Tribunal"."<strong></td>\n";
   	     echo "    <td><strong>" ."Cod.Trib."."<strong></td>\n";
   	     echo "    <td><strong>" ."Nombres"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Paterno"."<strong></td>\n";
   	     echo "    <td><strong>" ."A.Materno"."<strong></td>\n";
		 echo "    <td><strong>" ."RUT"."<strong></td>\n";
   	     echo "    <td><strong>" ."Delito"."<strong></td>\n";
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

</body>
</html>