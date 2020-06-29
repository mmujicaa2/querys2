<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style>
body {
	font-size: 90%;
	background-color: #F7D358;
}

td, th {
font-size: 90%
}
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
.EST8 {
	font-family: verdana;
	font-size: 9px;
}
.Estilo10 {
	font-family: verdana;
	font-size: 8px;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	font-variant: normal;
}
body,td,th {
	color: #000;
	font-weight: bold;
	font-size: 11px;
	font-family: Verdana;
}
.lista {   color:#00C;
   font-size: 9pt;
}


Verdanaaa {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
.Estilo11 .Estilo101 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
</style>

<head>
<meta http-equiv="Cache-Control" Content="no-cache" /> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ordenes de detenci&oacute;n</title>

<link rel="stylesheet" href="../jquery-ui-1.10.3/themes/smoothness/jquery-ui.css" /> 

<style type="text/css">
div.ui-datepicker{
 font-size:12px;
 background:#FFBF00;
}
.polis {
	font-size: 9px;
}
h1 .polis {
	font-size: 9px;
	top: auto;
	clip: rect(auto,auto,auto,auto);
}
h1 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 9px;
}
centrado {
	text-align: center;
}
verdana {
	font-family: Verdana, Geneva, sans-serif;
}
centrado titulo {
	text-align: center;
}
#ingreso_of p {
	text-align: center;
}
#ingreso_of p strong {
	font-size: 12px;
	text-decoration: underline;
	font-family: Verdana, Geneva, sans-serif;
}
.verdana11 {
	font-family: Verdana, Geneva, sans-serif;
}
.verdana11 {
	font-family: Verdana, Geneva, sans-serif;
}
.verdana11bold {
	font-family: Verdana, Geneva, sans-serif;
	font-weight: bold;
}
.verndana_nobold {
	font-weight: normal;
	font-family: Verdana, Geneva, sans-serif;
}
#ingreso_of table tr th a {
	font-size: 9px;
}
</style>

 
</head>

<body>

  <table width="29%" border="0" align="center">
    <tr>
      <th scope="col"><strong>Órdenes  vigentes</strong></th>
    </tr>
  </table>
  <hr />

<table width="201" border="0">
  <tr>
    <td width="195"><a href="ingresaordendet3.php" target="_self">Ver contraordenes vigente</a></td>
  </tr>
</table>

<?php
if (isset($_GET['orden']))
{
	if ($_GET['orden']=='fecha'){
		$orden="TGES_ORDENROL.FEC_SISTEMA DESC";
	}
	else{
		$orden="TATP_PERSONA.IDF_NOMBRES ASC";
	}
}
else{
	$orden="TATP_PERSONA.IDF_NOMBRES ASC";
	}

	
	
$color0 = "#F3E2A9";
$color1 = "#F3F781";
$color=$color0;

$conn = oci_connect('tg_penaltg', 'penaltg', 'rpenprod');
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
       TGES_ORDENROL.CRR_ORDEN 
  FROM (((JUDPENAL.TGES_ORDEN TGES_ORDEN 
       INNER JOIN JUDPENAL.TATP_PARTICIPANTE TATP_PARTICIPANTE 
          ON (TGES_ORDEN.CRR_PARTICIPANTE = TATP_PARTICIPANTE.CRR_IDPARTICIPANTE)) 
       INNER JOIN JUDPENAL.TGES_ORDENROL TGES_ORDENROL 
          ON (TGES_ORDENROL.CRR_ORDEN = TGES_ORDEN.CRR_IDORDEN)) 
       INNER JOIN JUDPENAL.TATP_PERSONA TATP_PERSONA 
          ON (TATP_PERSONA.CRR_LITIGANTE_ID = TATP_PARTICIPANTE.CRR_PERSONA)) 
       INNER JOIN JUDPENAL.TATP_CAUSA RIT 
          ON (RIT.CRR_IDCAUSA = TATP_PARTICIPANTE.CRR_CAUSA) 
 WHERE (RIT.COD_TRIBUNAL = 953) 
   AND (TGES_ORDEN.TIP_ORDEN = 1) 
   AND (TGES_ORDEN.FLG_VIGENCIA = 1) 
ORDER BY $orden

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

// Fetch the results of the query

print "<table align=\"center\" border='1'>\n";
echo "<tr>\n";
print "<tr bgcolor=\"#FFFF00\" class=\"Estilo101\">";
print "<th  nowrap=\"nowrap\">RUC</th>";
print "<th  nowrap=\"nowrap\">RIT</th>";
print "<th nowrap=\"nowrap\"><strong>Año</strong></th>";
print "<th nowrap=\"nowrap\">N° Orden</th>";
print "<th nowrap=\"nowrap\"><strong><strong><a href=\"ingresaordendet2.php?orden=nombres\">Nombres</strong></th>";
print "<th  nowrap=\"nowrap\">A.Paterno</th>";
print "<th  nowrap=\"nowrap\">A.Materno</th>";
print "<th  nowrap=\"nowrap\">RUT</th>";
print "<th  nowrap=\"nowrap\"><strong><a href=\"ingresaordendet2.php?orden=fecha\">Fecha</strong></th>";
print "<th >Doc</th>";
print  "</tr>";


while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$i=0;
    print "<tr bgcolor=$color >";
    foreach ($row as $item) {
		if ($i==9){
			//print "<td>" . ($item) . "</td>\n";			
			print  "<td>" . "<a href=\"http://portal2013.pjud.cl/causas/rpp/muestra_doc.php?id=$item\"><img src =\"pdf.gif\" border=0></a>". "</td>\n";
			}
		else{
			print "    <td>" . ($item) . "</td>\n";
		}
		$i++;
    }
    print "</tr>\n";
	  
     if ($color == $color0) {
				$color = $color1;
			} 
			else {
				$color = $color0;
			}
	

}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?> 

</body>
</html>


