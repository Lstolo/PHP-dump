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
      <video oncontextmenu="return false" controls controlsList="nodownload" onclick=" if (!click){this.play(); click=true;}else{this.pause(); click=false;}" id="myVid" width="100%" height="80%">
            
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
$sqlCheckVideo1 = "SELECT materiales.tipo, materiales.directorio, materiales.nombre, materiales.descripcion FROM materiales JOIN propiedadmaterial ON propiedadmaterial.idMaterial = materiales.idMaterial WHERE propiedadmaterial.idUsuario = $userId AND materiales.idMaterial = $idMaterial AND materiales.tipo = 'video'";
$sqlCheckVideo2 = "SELECT materiales.tipo, materiales.directorio, materiales.nombre, materiales.descripcion FROM materiales JOIN cursos ON cursos.idCurso = materiales.idCurso JOIN propiedadcurso ON propiedadcurso.idCurso = cursos.idCurso WHERE materiales.idMaterial = $idMaterial AND propiedadcurso.idUsuario = $userId AND materiales.tipo = 'video'"; 
$results = false;

if ($dbconn->query($sqlCheckVideo1)->num_rows > 0) {
      echo "You got the material";
      $sqlCheckVideo = $sqlCheckVideo1;
      $results = true;
}elseif ($dbconn->query($sqlCheckVideo2)->num_rows > 0) {
      echo "You got the course";
      $sqlCheckVideo = $sqlCheckVideo2;
      $results = true;
}

echo $sqlCheckVideo;
# FALTA CHEQUEAR QUE SEA UN VIDEO EL MATERIAL
    if ($results) {
      
#Mostrar el video
$videoInfo = $dbconn->query($sqlCheckVideo)->fetch_assoc();
$videoDir = $videoInfo["directorio"];
$videoName = $videoInfo["nombre"];
$videoDesc = $videoInfo["descripcion"];

echo "<script>document.getElementById('tit').innerHTML = '<h1>$videoName</h1>'</script> <div class='subhead'>$videoDesc</div>";

      #Darle fuente al video
      echo "<script>var videoPlayer = document.getElementById('myVid'); videoPlayer.src = '$videoDir';</script>";
    }else {
      echo "<p>No hemos encontrado el video en tu biblioteca :(</p>";
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