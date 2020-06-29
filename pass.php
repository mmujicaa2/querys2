<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	background-color: #CF9;
}
.verdana10 {	font-size: 14px;
}
.verdana10 {	font-size: 10px;
}
</style>
</head>

<body>
<p>PASS SIAGJ</p>
<table width="957" height="60" border="1">
  <tr>
    <td width="888" class="verdana10"><strong class="verdana12">Por nombre</strong></td>
  </tr>
  <tr>
    <td class="verdana10"><form id="formsiagj1" name="formsiagj1" method="post" action="passsiagj.php">
      <p id="formimputado">Nombre:
  <input type="text" name="nombresiagj" id="nombresiagj" />
        A.Paterno:
        <input type="text" name="apellidosiagj" id="apellidosiagj" />
        <input type="submit" name="Enviar2" id="Enviar2" value="Enviar" onclick="valida_imp()"/>
      </p>
    </form></td>
  </tr>
</table>
<table width="957" height="60" border="1">
  <tr>
    <td width="888" class="verdana10"><strong class="verdana12">Por cod tribunal</strong></td>
  </tr>
  <tr>
    <td class="verdana10"><form id="formsiagj2" name="formsiagj2" method="post" action="passsiagj2.php">
      <p id="formimputado2">Cod tribunal:
        <input type="text" name="codtribunal" id="codtribunal" />
        <input type="submit" name="Enviar" id="Enviar" value="Enviar" onclick="valida_imp()"/>
      </p>
    </form></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>