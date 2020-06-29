<html>
<head>
  <title>Código de Fiscal / Defensor</title>
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
    <a style="color:#d9534f" class="center navbar-text navbar-center text-light">Código de Fiscal / Defensor</a>
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
$nombrefd=trim(strtoupper($_POST['nombrefd']));
$apaternofd=trim(strtoupper($_POST['apaternofd']));
$tipo=$_POST['tipofd'];

if ($tipo==1)
{
$querypass= 
	"SELECT tatp_persona.idf_nombres,tatp_persona.idf_paterno,tatp_persona.idf_materno,TATP_FISCAL.COD_FISCAL, 		TATP_FISCAL.EST_VIGENCIA,TATP_FISCAL.COD_FISCALIA,TATP_FISCALIA.GLS_FISCALIA,TATP_PERSONA.GLS_EMAIL
FROM TATP_FISCAL, TATP_PERSONA,TATP_FISCALIA
WHERE TATP_PERSONA.CRR_LITIGANTE_ID=TATP_FISCAL.CRR_PERSONA
AND    TATP_FISCALIA.COD_FISCALIA=TATP_FISCAL.COD_FISCALIA
AND    TATP_PERSONA.IDF_NOMBRES LIKE '$nombrefd%'
AND    TATP_PERSONA.IDF_PATERNO LIKE '$apaternofd%'
";
} 

elseif ($tipo==2)
{
$querypass= "SELECT tatp_persona.idf_nombres,tatp_persona.idf_paterno,tatp_persona.idf_materno,TATP_DEFENSOR.COD_DEFENSOR, 		TATP_DEFENSOR.EST_VIGENCIA,TATP_DEFENSOR.COD_DEFENSORIA,TATP_DEFENSORIA.GLS_DEFENSORIA,TATP_PERSONA.GLS_EMAIL
FROM TATP_DEFENSOR, TATP_PERSONA,TATP_DEFENSORIA
WHERE TATP_PERSONA.CRR_LITIGANTE_ID=TATP_DEFENSOR.CRR_PERSONA
AND    TATP_DEFENSORIA.COD_DEFENSORIA=TATP_DEFENSOR.COD_DEFENSORIA
AND    TATP_PERSONA.IDF_NOMBRES LIKE '$nombrefd%'
AND    TATP_PERSONA.IDF_PATERNO LIKE '$apaternofd%'";
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
  	     echo "    <td><strong>" ."Nombres"."<strong></td>\n";
  	     echo "    <td><strong>" ."A.Paterno"."<strong></td>\n";
  	     echo "    <td><strong>" ."A.Materno"."<strong></td>\n";
 	     echo "    <td><strong>" ."Cod. F/D"."<strong></td>\n";
	     echo "    <td><strong>" ."Vigencia"."<strong></td>\n";
 	     echo "    <td><strong>" ."Cod Establ."."<strong></td>\n";
  	     echo "    <td><strong>" ."Ciudad"."<strong></td>\n";
   	     echo "    <td><strong>" ."Correo"."<strong></td>\n";
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

<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>

</body>
</html>