<html>
<body class="CENTRO">
 ****SIGA LA SECUENCIAAAAAA****
 </BR>
  </BR>
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
</style>
<?php

$conn = oci_connect('tg955_ayuda', 'olimpo', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}
$nombre=trim(strtoupper($_POST['nombresiagj']));
$apellido=trim(strtoupper($_POST['apellidosiagj']));

$querypass= "select * 
from tatp_passwordexpired,tatp_persona,tatp_usuario,tatp_cuenta
where tatp_persona.crr_litigante_id=tatp_usuario.crr_persona
  and tatp_usuario.idf_usuario=tatp_cuenta.idf_usuario
  and tatp_cuenta.crr_idcuenta=tatp_passwordexpired.crr_cuenta
  and tatp_persona.idf_nombres like '%$nombre%'
  and tatp_persona.idf_paterno like '%$apellido%'
    ORDER BY tatp_persona.idf_paterno desc";



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
print "<table border='1'>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
		
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
