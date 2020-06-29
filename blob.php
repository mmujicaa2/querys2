<!DOCTYPE>
<html>
<meta http-equiv="content-type" content="text/html; charset=ISO8859-9"></meta>
<body class="CENTRO">
  </BR>
 </span>
 
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

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod' ,'WE8ISO8859P9' );
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}

$querypass= " SELECT TDOC_DOCUMENTODOC.BLOB_DOCUM
FROM  TDOC_DOCUMENTODOC
WHERE  TDOC_DOCUMENTODOC.CRR_DOCUMENTO=58148360
";

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

			$row = oci_fetch_assoc($stid);
			//header('Content-Type:doc');
			//header('Content-Disposition:filename=documento.doc');
			echo  $row["BLOB_DOCUM"]->read(9999999999);

oci_free_statement($stid);
oci_close($conn);

?> 
