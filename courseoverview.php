<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title id="title">Curso</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
      <div style="color:white;background-color:green" id='notifier'></div>
      <?php
     
      
      # conectar a la base de datos
      if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
        $courseid = $dbconn->real_escape_string($_GET["idCourse"]);     
      
      # Ver si el curso esta disponible
      $selectCourseInfoSQL = "SELECT * FROM cursos WHERE idCurso = $courseid";

      if ($courseInfoQuery = $dbconn->query($selectCourseInfoSQL) AND $courseInfoQuery->num_rows == 1) {
            
      
      # Ver si el usuario ha iniciado sesion
      $userId = $dbconn->real_escape_string($_COOKIE["id"]);
      $password = $dbconn->real_escape_string($_COOKIE["pass"]);
    
      $dblogin = "SELECT id FROM users WHERE id = '$userId' AND clave = '$password'";
      if ($dbconn->query($dblogin)->num_rows > 0) {
            $loggedIn = true;
            # Ver si el usuario ha comprado el curso
            $seeProperySQL = "SELECT idProp FROM propiedadcurso WHERE idCurso = $courseid AND idUsuario = $userId";
            if ($dbconn->query($seeProperySQL)->num_rows > 0) {
                  $possesion = true;
                  
            }else{
                  $possesion = false;
            }
      }else {
            echo "<script>document.getElementById('notifier').innerHTML = '<h2>⚠️No has iniciado sesion. <a href='index.php'>Accede</a> para disfrutar del esplendor BinaAcademy</h2> <br> <h3>No tienes usuario? Crear tu cuenta es gratis!</h3>'</script>";
            $possesion = false;
            $loggedIn = false;
      }
     # Mostrar el curso
     $courseInfo = $courseInfoQuery->fetch_assoc();
     echo "<script>document.getElementById('title').innerHTML = '" . $courseInfo["NombreCusro"] . "'</script>";
     echo "<div class='header' id='overvw'><h1>" . $courseInfo["NombreCusro"] . "</h1></div>";
     echo "<div class='subhead'><p>" . $courseInfo["Campo"] . ": " . $courseInfo["Nivel"] . "</p>  <p>Por: " . $courseInfo["autor"] . "</p></div>";
     # Ofrecer al usuario sin posesion
      if (!$possesion) {
            echo "<h7>Precio: " . $courseInfo["Precio"] . "</h7><a href='precheckout.php?itemId=" . $courseInfo["idCurso"] . "&itemType=course'> COMPRAR</a> <div class='subhead'><h6>Puedes pinchar en un material en particular para acceder a este directamente, sin tener que comprar todo el curso.</h6></div>";
      }
      # Mostrar los materiales

      $sqlMaterialFetch = "SELECT * FROM materiales WHERE idCurso = $courseid";
      if ($materialQuery = $dbconn->query($sqlMaterialFetch) AND $materialQuery->num_rows > 0) {
            while ($materialInfo = $materialQuery->fetch_assoc()) {
                  switch ($materialInfo["tipo"]) {
                        case 'video':
                              $icon = "🎥";
                              break;
                              case 'doc':
                                    $icon = "📎";
                                    break;
                              
                        default:
                              # code..
                              $redirec = "index.php";
                              break;
                  }
                  echo "<div class='materialShow' onclick=\"window.location = 'materialoverview.php?idMat=" . $materialInfo["idMaterial"] . "'\"><h3>$icon " . $materialInfo["nombre"] . "</h3> <p>Acerca: " . $materialInfo["descripcion"] . "</p> <a></a></div>";
            }
      }else {
            echo "<h4 style='color:grey'>No estan disponibles los materiales</h4>";
      }


     }else {
      echo "no pudimos encontrar el curso :<";
     }
}else { 
            echo "conexion fallida con la BD :(";      }
      ?>
</body>
</html>