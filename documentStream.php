<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>BinaPlayer</title>
       <script src="videostream.js"></script>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
     
     <div class="header" id="tit" onclick="window.location = 'panel.php'"></div>
      <div height="70%"><embed type="application/pdf" height="600px" oncontextmenu="return false" id="mydoc" width="100%" height="80%"></div></embed>
            
<?php
if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
      # Log in
      $userId = $dbconn->real_escape_string($_COOKIE["id"]);
      $password = $dbconn->real_escape_string($_COOKIE["pass"]);
      $idMaterial = $dbconn->real_escape_string($_GET["idMat"]);
      # verificar sesion
      $dblogin = "SELECT id FROM users WHERE id = '$userId' AND clave = '$password'";

      if ($loginquery = $dbconn->query($dblogin)) {
         if ($loginquery->num_rows > 0) {
            
      #Verificar si el material le pertenece al usuario
# tanto si el video le pertenece o si el curso le pertenece
$sqlCheckdoc1 = "SELECT materiales.tipo, materiales.directorio, materiales.nombre, materiales.descripcion FROM materiales JOIN propiedadmaterial ON propiedadmaterial.idMaterial = materiales.idMaterial WHERE propiedadmaterial.idUsuario = $userId AND materiales.idMaterial = $idMaterial AND materiales.tipo = 'doc'";
$sqlCheckdoc2 = "SELECT materiales.tipo, materiales.directorio, materiales.nombre, materiales.descripcion FROM materiales JOIN cursos ON cursos.idCurso = materiales.idCurso JOIN propiedadcurso ON propiedadcurso.idCurso = cursos.idCurso WHERE materiales.idMaterial = $idMaterial AND propiedadcurso.idUsuario = $userId AND materiales.tipo = 'doc'"; 
$results = false;

if ($dbconn->query($sqlCheckdoc1)->num_rows > 0) {
      
      $sqlCheckdoc = $sqlCheckdoc1;
      $results = true;
}elseif ($dbconn->query($sqlCheckdoc2)->num_rows > 0) {
      
      $sqlCheckdoc = $sqlCheckdoc2;
      $results = true;
}


# FALTA CHEQUEAR QUE SEA UN VIDEO EL MATERIAL
    if ($results) {
      
#Mostrar el video
$docInfo = $dbconn->query($sqlCheckdoc)->fetch_assoc();
$docDir = $docInfo["directorio"];
$docName = $docInfo["nombre"];
$docDesc = $docInfo["descripcion"];

echo "<script>document.getElementById('tit').innerHTML = '<h1>$docName</h1>'</script> <div class='subhead'>$docDesc</div>";

      #Darle fuente al video
      echo "<script>var docPlayer = document.getElementById('mydoc'); docPlayer.src = '$docDir#toolbar=0';</script>";
    }else {
      echo "<p>No hemos encontrado el doc en tu biblioteca :(</p>";
    }
}else {
      echo "<script>window.location = 'index.php'</script>";
}
}else {
      echo "consulta de login con BD fallida";
}}else {
      # code...
      echo "conexion con BD fallida";
}
?>


</body>
</html>