<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Portal de usuario</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
      <script src="panelscript.js"></script>
      <link rel="icon" type="image/png" href="logobina.png" />
      <?php
      
# Hacer conexion con la base de datos


if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
      # Log in
     
      $userId = $dbconn->real_escape_string($_COOKIE["id"]);
      $password = $dbconn->real_escape_string($_COOKIE["pass"]);
    
      $dblogin = "SELECT mail ,id, nombre, apellido, saldo FROM users WHERE id = '$userId' AND clave = '$password'";

                  if ($loginquery = $dbconn->query($dblogin)) {
                     if ($loginquery->num_rows > 0) {
                      $userdatassoc = $loginquery->fetch_assoc();
                        $userid = $loginquery->fetch_assoc()["id"];
                      
                        # Consultar saldo, cursos y videos

                        # Mostrar links para ver
                        echo "<div class='header'><h1>Hola, " . $userdatassoc["nombre"] . " " . $userdatassoc["apellido"] . "</h1><p>Saldo disponible: " . $userdatassoc["saldo"] . " puntos</p></div>";
                        echo "<div class='subhead'> <a href='market.php'>EXPLORAR MATERIALES    </a><a href='recargas.php'>COMPRAR PUNTOS </a><a href='questionpanel.php'>PREGUNTAS A PROFESORES</a><p onclick=\"document.cookie = 'id=0;pass=0';document.cookie = 'pass=0'; window.location = 'index.php'\" style=\"border-color:black\" >SALIR🚶🏻</p></div>";

                        # Consultar por cursos completos

                        $fetchCursosSql = "SELECT cursos.idCurso, cursos.`NombreCusro`, cursos.`Nivel`, cursos.`Campo`, cursos.`autor` FROM `cursos` JOIN propiedadcurso ON cursos.idCurso = propiedadcurso.idCurso WHERE propiedadcurso.idUsuario = $userId";
                        if ($cursosResultQuery =$dbconn->query($fetchCursosSql)) {
                              if ($cursosResultQuery->num_rows > 0) {
                                     echo "<h2 id='cursos'>Tus cursos:</h2>";
                                    while ($usercoursesinfo = $cursosResultQuery->fetch_assoc()) {
                                         
                                          echo "<h3>" . $usercoursesinfo["NombreCusro"] . "</h3><h5>Nivel: " . $usercoursesinfo["Nivel"] ."</h5> <p>" . $usercoursesinfo["autor"] . ", " . $usercoursesinfo["Campo"] . " <br> <a href='courseoverview.php?idCourse=" . $usercoursesinfo["idCurso"] . "'>VER</a></p>";
                                    }
                              }else {
                                    
                                    echo "<h3 color='grey'>No dispones cursos enteros a tu propiedad</h3>";
                              }
                        }else {
                              # code...
                              echo "error al cargar los cursos";
                        }
      
                        # Cargar materiales
                        $dbFetchMaterialsSql = "SELECT materiales.descripcion, materiales.idMaterial, materiales.tipo, materiales.nombre, cursos.NombreCusro FROM materiales JOIN cursos ON cursos.idCurso = materiales.idCurso JOIN propiedadmaterial ON materiales.idMaterial = propiedadmaterial.idMaterial WHERE propiedadmaterial.idUsuario = $userId";
                   
                        if ($fetchMaterialQuery = $dbconn->query($dbFetchMaterialsSql)) {
                        # verificar si hay
                        if ($fetchMaterialQuery->num_rows > 0) {
                              echo "<h2 id='materials'>Tus materiales sueltos:</h2>";
                              while ($materialsQueryAssoc = $fetchMaterialQuery->fetch_assoc()) {
                                    $materialName = $materialsQueryAssoc["nombre"];
                                    $materialDesc = $materialsQueryAssoc["descripcion"];
                                    $materialType = $materialsQueryAssoc["tipo"];
                                    $materialId = $materialsQueryAssoc["idMaterial"];
                                    $materialorigin = $materialsQueryAssoc["NombreCusro"];
                                    switch ($materialType) {
                                          case 'video':
                                                $icon = "🎥";
                                                break;
                                          case 'doc':
                                                $icon = "📎";
                                                break;
                                          default:
                                                # code...
                                                break;
                                    }

                          
                              echo "<div><h3>$icon $materialName</h3> <h4>$materialorigin</h4> <p>$materialDesc</p> <a href='materialoverview.php?idMat=$materialId'>ABRIR</a></div>";
                              }
                        }else {
                              
                              echo "<h3 color='grey'>No dispones materiales sueltos a tu propiedad</h3>";
                        }
                       }else {
                        echo "No se pudo cargar materiales";
                       }
         
                     }else {
                        echo "<script>window.location = 'index.php'</script>";
                     }

                  
               }else {
                  echo "eror 103";
               }

      
  }else {
      echo "conexion fallida con la base de datos :(";
  }  
      
      ?>
</body>
</html>