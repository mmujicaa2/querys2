ind<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<title>Búsqueda de Ordenes de Detención</title>


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
    <a style="color:#d9534f" class="navbar-text navbar-center text-light">Estado Contra Ordenes de Detención</a>
    </nav>
</div>



<div class="container ">
  
    <form id="formres" name="formres" method="post" action="ingresaordendet3.php">

       <p class="text-dark form-group form-inline">Seleccione fechas de búsqueda</p>
       <div class="form-inline form-group ">

       <div class="form-group mb-2 mr-sm-2">
                  <input  id="datepicker3" class="form-control form-group" name="finicioev" placeholder="Fecha Inicio" required>
                    <label for="datepicker3">
                      <span class="input-group-text" id="basic-addon2" for="datepicker" >
                        <i class="fa fa-calendar " style="font-size:24px"></i>
                      </span>
                    </label>
                </div>

      <div class="form-group mb-2 mr-sm-2">
                  <input  id="datepicker2" class="form-control form-group" name="ffinev" placeholder="Fecha Final" required>
                    <label for="datepicker2">
                      <span class="input-group-text" id="basic-addon2" for="datepicker2" >
                        
                        <i class="fa fa-calendar" style="font-size:24px"></i>
                      </span>
                    </label>
                </div>
       </div>
                
        
       
        
        <p class="text-dark form-group form-inline">Código de Tribunal</p>
        <input class="form-control form-group" name="ctrib" type="text" id="ctrib" value="928" size="6" maxlength="6" />
        
      
        
        <button id="Enviar6" type="button bnt btn-lg" class="btn btn-outline-primary btn-lg col-lg">Buscar</button>

  
    </form>
</div><<!-- Cierre div container principal-->


<div class="footer">
  <p class="rights fixed-bottom"><a href="mailto:mmujica@pjud.cl">Desarrollado por Marcelo Mujica</a></p>
</div>

</body>
</html>