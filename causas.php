<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>

<title>Querys SIAGJ</title>

<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo "error en conexion";
}

$querypass= "select * from tg_hitoact order by gls_despliegue asc";

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
?> 
<!-- Jquery --> 
<script src="js/jquery.min.js"></script>
<script src="js/eventos.js"></script>
<!--Datepicker bootstrap-->
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.es.min.js"></script>
<link href="css/bootstrap-datepicker.css" rel="stylesheet"/>

<!-- Viewport --> 
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

<!-- Bootstrap -->
<link rel="stylesheet" href="js/bootstrap.min.css">
<script src="js/bootstrap.min.js"></script>
<link href="js/font-awesome.min.css" rel="stylesheet">

<!--Estilos CSS-->
<link rel="stylesheet" href="css/estilo.css">

</head>

<body>

  <div class="container-fluid">
    <nav class="navbar navbar-nav  navbar-expand-lg bg-secondary">
    <a style="color:#d9534f" class="navbar-text navbar-center text-light">Búsqueda de Ingresos entre fechas</a>
    </nav>
</div>

<div class="container ">


      <form id="formsent" name="formsent" method="post" action="sentxfecha.php">

   <p class="text-dark form-group form-inline">Seleccione fechas de búsqueda:</p>
       <div class="form-inline form-group ">

       <div class="form-group mb-2 mr-sm-2">
                  <input  id="datepicker" class="form-control form-group" name="finiciosent" placeholder="Fecha Inicio" required>
                    <label for="datepicker">
                      <span class="input-group-text" id="basic-addon2" for="datepicker" >
                        <i class="fa fa-calendar " style="font-size:24px"></i>
                      </span>
                    </label>
                </div>

      <div class="form-group mb-2 mr-sm-2">
                  <input  id="datepicker2" class="form-control form-group" name="ffinsent" placeholder="Fecha Final" required>
                    <label for="datepicker2">
                      <span class="input-group-text" id="basic-addon2" for="datepicker2" >
                        
                        <i class="fa fa-calendar" style="font-size:24px"></i>
                      </span>
                    </label>
                </div>
       </div>
                
<p class="text-dark form-group form-inline">Delito (puede ser parte del texto del delito):</p>
        <input class="form-control form-group" name="glosasent" type="text" id="glosasent" > 
<p class="text-dark form-group form-inline">Código de Tribunal:</p>
        <input class="form-control form-group" name="ctribsent" type="text" id="ctribsent"value="928" >   
        <button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>

    </form>

</div><<!-- Cierre div container principal-->

</body>
</html>
