<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<title>Búsqueda código de Fiscal / Defensor</title>


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
    <a class="navbar-text navbar-center text-light">Búsqueda de código de Fiscal / Defensor</a>
    </nav>
</div>

<div class="container">
<p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Cod. Fiscal/Defensor (puede ser parte de nombres Ej: lui casti) </p>
<div class="card-title blockquote-footer">Nota: Escribir nombres hasta antes de tíldes o ñ</div>

      <form id="formrfd" name="formrfd" method="post" action="codfd.php">
<p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Nombre:</p>

<input class="form-control form-group  mb-2 mr-sm-2" type="text" id="nombrefd" name="nombrefd" />
  <p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Apellido Paterno:</p>

<input class="form-control form-group  mb-2 mr-sm-2" type="text" id="apaternofd" name="apaternofd" />
<p class="text-dark form-group form-inline mb-2 mr-sm-2 ">Tipo:</p>
        <select class="form-control form-group" name="tipofd" id="tipofd">
          <option value="1">Fiscal</option>
          <option value="2">Defensor</option>
        </select>
<button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>
        </span>
    </form>

</div>    
</body>
</html>
