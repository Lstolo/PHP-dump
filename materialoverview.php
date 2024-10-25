<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
      <?php
      if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
            $userId = $dbconn->real_escape_string($_COOKIE["id"]);
            $password = $dbconn->real_escape_string($_COOKIE["pass"]);
      
      # Clasificar el material 

      # Ver pertenencia y redireccionar
      $idMat = $dbconn->real_escape_string($_GET["idMat"]);
      $sqlMaterial = "SELECT * FROM materiales WHERE idMaterial = $idMat";
      if ($queryMat = $dbconn->query($sqlMaterial) AND $queryMat->num_rows == 1) {
            $matASS = $queryMat->fetch_assoc();
            $matType = $matASS["tipo"];

            switch ($matType) {
                  case 'video':
                        $headerGO = "videostream.php?idMat=$idMat";
                        $icon = "🎥";
                        break;
                  
                  case 'doc':
                        $headerGO = "documentStream.php?idMat=$idMat";
                        $icon = "📎";
                        break;
                  default:
                        $headerGO = "index.php";
                        break;}

            # Hacer login
            $dblogin = "SELECT id, saldo FROM users WHERE id = '$userId' AND clave = '$password'";
            $loggedIn = false;
      if ($dbconn->query($dblogin)->num_rows > 0) {
            $loggedIn = true;

            $checkProperty1 = "SELECT idProp FROM propiedadmaterial WHERE idUsuario = $userId AND idMaterial = $idMat;";
            $checkProperty2 = "SELECT cursos.idCurso FROM cursos JOIN propiedadcurso ON propiedadcurso.idCurso = cursos.idCurso JOIN materiales ON materiales.idCurso = cursos.idCurso WHERE propiedadcurso.idUsuario = $userId AND materiales.idMaterial = $idMat;";
            $prop = false;
            if ($dbconn->query($checkProperty1)->num_rows > 0) {
                  # Tiene propiedad 
                  $prop = true;
                  
            }elseif ($dbconn->query($checkProperty2)->num_rows > 0) {
                  $prop = true;
            }
            if ($prop) {
                  echo "<h1>Mostrando recurso, suerte! 😽</h1>";
                 
                  
                  echo "<script>window.location = '$headerGO'</script>";
                  
            }
            # El usuario no dispone del material, mostrar info

            # Cargar datos del material, consultar por curso
            $matDesc = $matASS["descripcion"];
            $matName = $matASS["nombre"];
            $matPrice = $matASS["precio"];
            $buyAnchor = "";
            if ($loggedIn) {
               
                  $buyAnchor = "<a style='background-color:white' href='precheckout.php?itemId=$idMat&itemType=material'>COMPRAR</a>";
            }
            echo "<div class=\"header\" onclick=\"window.location = 'panel.php'\"><h1>$icon $matName</h1><p>Precio: $matPrice</p>$buyAnchor</div><div class='subhead'><p>$matDesc</p></div>";


      }
      # Ver el material
      # Habilitar overview
      }else {
            echo "Material no disponible";
      }
      }else {
            echo "Error al conectar con la BD";
      }
      ?>
</body>
</html>