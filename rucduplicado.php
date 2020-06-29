<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ordenes</title>
<style type="text/css">
.titulo {
}
</style>
</head>
<p><strong>****RUC DUPLICADO - RIT X RUC X TRIBUNAL****</strong></p>
<p>&nbsp;</p>
</body>
</html>

<style type="text/css">
body {
	background-color: #CF9;
	text-align: center;
}
.centro {
	text-align: center;
}
.CENTRO {
	text-align: center;
}
.titulo {
	font-weight: bold;
	text-align: center;
}
.centrado {
	text-align: center;
}
</style>
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
print "<table border='1'>\n";
	 echo "<tr>\n";
  	     echo "    <td><strong>" ."RUC"."<strong></td>\n";
  	     echo "    <td><strong>" ."RIT"."<strong></td>\n";
  	     echo "    <td><strong>" ."AÑO"."<strong></td>\n";
  	     echo "    <td><strong>" ."TRIB"."<strong></td>\n";
 	     
    echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

    echo "<tr>\n";
    foreach ($row as $item) {
        //echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "nowrap") . "</td>\n";
	     echo "    <td>" . ($item )."</td>\n";
		
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
