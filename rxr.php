<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<title>Querys SIAGJ Participantes</title>


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

</head>

<body>

  <div class="container-fluid">
    <nav class="navbar navbar-nav  navbar-expand-lg bg-secondary">
    <a class="navbar-text navbar-center text-light">Búsqueda de RIT por RUC  / RUC por RIT</a>
    </nav>
</div>

<div class="container"> 

<div class="card">
     <div class="card-header">Búsqueda por RUC</div>
       <div class="card-body">
      <form id="formrucduplicado" name="formrucduplicado" method="post" action="rucduplicado.php">
        <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">RUC</p>
        <input class="form-control form-group mb-2 mr-sm-2" type="text" id="rucduplicado" name="rucduplicado"  required />
                <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Código de Tribunal</p>
        <input class="form-control form-group mb-2 mr-sm-2" type="text" id="ctrib" name="ctrib"  required  />
        <button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>
      </form>
 </div>

<div class="card-header">Búsqueda por RIT</div>
       <div class="card-body">
            <form id="formrucxrit" name="formrucxrit" method="post" action="rucxrit.php">
            
            <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">RIT</p>
            <input class="form-control form-group mb-2 mr-sm-2" type="text" id="rit" name="rit"  required />
            <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Año</p>
            <input class="form-control form-group mb-2 mr-sm-2" type="text" id="anio" name="anio"  required />
            
            <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Código de Tribunal</p>

            <input class="form-control form-group mb-2 mr-sm-2" type="text" id="ctrib" name="ctrib"  required />
            <button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>
            </form>

       </div>

</div>
</div>

<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>


</body>
</html>
