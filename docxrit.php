<html>
<body class="CENTRO">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

$querypass= "SELECT TATP_CAUSA.IDF_ROLUNICO,
       TATP_CAUSA.IDF_ROLINTERNO,
       TATP_CAUSA.FEC_ERA,
       TDOC_DOCUMENTO.GLS_DESCRIPCION,
       TGES_EVENTO.FEC_EVENTO,
       TGES_EVENTO.EST_EVENTO,
       TDOC_DOCUMENTODOC.BLOB_DOCUM
  FROM    (   (   JUDPENAL.TGES_EVENTO TGES_EVENTO
               INNER JOIN
                  JUDPENAL.TATP_CAUSA TATP_CAUSA
               ON (TGES_EVENTO.CRR_CAUSA = TATP_CAUSA.CRR_IDCAUSA))
           INNER JOIN
              JUDPENAL.TDOC_DOCUMENTO TDOC_DOCUMENTO
           ON (TDOC_DOCUMENTO.CRR_IDDOCUMENTO = TGES_EVENTO.CRR_DOCUMENTO))
       INNER JOIN
          JUDPENAL.TDOC_DOCUMENTODOC TDOC_DOCUMENTODOC
       ON (TDOC_DOCUMENTODOC.CRR_DOCUMENTO = TDOC_DOCUMENTO.CRR_IDDOCUMENTO)
 WHERE     (TATP_CAUSA.IDF_ROLINTERNO = 219)
       AND (TATP_CAUSA.FEC_ERA = 2014)
       AND (TATP_CAUSA.COD_TRIBUNAL = 953)
ORDER BY TGES_EVENTO.FEC_EVENTO ASC";


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
   	     echo "    <td><strong>" ."Glosa"."<strong></td>\n";
   	     echo "    <td><strong>" ."Fecha"."<strong></td>\n";
   	     echo "    <td><strong>" ."Estado"."<strong></td>\n";
   	     echo "    <td><strong>" ."Documento"."<strong></td>\n";
   	   
    echo "</tr>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$i=1;
    print "<tr>\n";
    foreach ($row as $item) {
		if ($i==7){
			
			//header('Content-Type:application/msword');
			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			//header("Content-Disposition:filename=documento.docx");			
			//header('Content-Transfer-Encoding: base64');
			print $item->read(2000000);
		}
		else{
				//print "    <td>" . ($item) . "</td>\n";
			}
    $i++;
	}
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 
