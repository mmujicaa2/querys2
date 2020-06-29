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
    <a class="navbar-text navbar-center text-light">Búsqueda de Participantes por Nombre</a>
    </nav>
</div>

<div class="container">

  <div class="card">
     <div class="card-header">Búsqueda por Nombre</div>
    <div class="card-body">
      <div class="card-title blockquote-footer">Notas: 1. Escribir nombres hasta antes de tíldes o ñ,  2. La búsqueda puede demorar dependiendo de los parámetros de búsqueda</div>

          <form id="formimputado" name="formimputado" method="post" action="impdelito.php">
            <div class="form-inline form-group ">
                <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Nombre:</p>
                  <input class="form-control form-inline mb-2 mr-sm-2" type="text"  id="nombreimp"name="nombreimp"  />
             
                <p class="text-dark form-group form-inline mb-2 mr-sm-2">Apellido Paterno:</p>
                  <input class="form-control form-group  mb-2 mr-sm-2" type="text" id="apaternoimp" name="apaternoimp" />
             
                <p class="text-dark form-group form-inline   mb-2 mr-sm-2 ">Apellido Materno:</p>
                <input class="form-control form-group mb-2 mr-sm-2" type="text" id="amaternoimp" name="amaternoimp"   />
            </div>

            <p class="text-dark form-group form-inline">Código de Tribunal</p>
              <input class="form-control form-group form-inline" type="text" id="codtrib" name="codtrib"  value="928"  maxlength="6" />

              <p class="text-dark form-group">Tipo</p>
              
              <select class="form-control form-group" id="tipo" name="tipo" >
                <option value="2">Imputado</option>
                <option value="5">Victima</option>
                <option value="7">Fiscal</option>
                <option value="8">Defensor</option>
                <option value="12">Testigo</option>
                <option value="14">Perito</option>
              </select>

            <button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>

          </form>

    </div><!-- Card-body -->
  </div> <!-- Card -->

 <div class="card">
     <div class="card-header">Búsqueda por RUT</div>
    <div class="card-body">
      
      <form id="formimputado2" name="formimputado2" method="post" action="impdelito2.php">
        <div class="form-inline form-group ">
          <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">RUT:</p>
            <input class="form-control form-group mb-2 mr-sm-2" type="text" name="rutimp" id="rutimp"  placeholder="11111111-1" required />

            <p class="text-dark form-group form-inline mb-2 mr-sm-2">Código de Tribunal:</p>
            <input class="form-control form-group mb-2 mr-sm-2" name="codtrib2" type="text" id="codtrib2" value="928" size="5" maxlength="5" />
            
            <select class="form-control form-group mb-2 mr-sm-2" name="tipo2" id="tipo2">
              <option value="2">Imputado</option>
              <option value="5">Victima</option>
              <option value="7">Fiscal</option>
              <option value="8">Defensor</option>
              <option value="12">Testigo</option>
              <option value="14">Perito</option>
            </select>
        </div>
        <button id="Enviar7" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>
    </form>

</div>
</div>

</div>
    

<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>


</body>
</html>
