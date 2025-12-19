<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Comprar item</title>
</head>
<body>
      
     
      <link rel="stylesheet" href="genstyle.css">
      <div id="notifier" style="background-color:red;color:white;"></div>
      <?php
      

      # Conectar con BD
      if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
            $idItem = $dbconn->real_escape_string($_GET["itemId"]);
      $itemType = $dbconn->real_escape_string($_GET["itemType"]);
    
            $userId = $dbconn->real_escape_string($_COOKIE["id"]);
            $password = $dbconn->real_escape_string($_COOKIE["pass"]);
          
            $dblogin = "SELECT mail ,id, nombre, apellido, saldo FROM users WHERE id = '$userId' AND clave = '$password'";
      
                        if ($loginquery = $dbconn->query($dblogin)) {
                           if ($loginquery->num_rows > 0) {
                            $userdatassoc = $loginquery->fetch_assoc();
                              

      # Hacer login

      # Verificar saldo y precio del item 
      if ($itemType == "course") {
            # hacer select para el curso y id, ver precio, comparar con saldo y cargar la consulta y el overview
            $sqlSearchMaterial = "SELECT * FROM cursos WHERE idCurso = $idItem";
            if ($materialquery = $dbconn->query($sqlSearchMaterial) AND $materialquery->num_rows == 1) {
                  $materialInfo = $materialquery->fetch_assoc();

                  $courseName = $materialInfo["NombreCusro"];
                  $nivel = $materialInfo["Nivel"];
                  
                  $precio = $materialInfo["Precio"];
                  
                  $campo = $materialInfo["Campo"];
                  
                  $autor = $materialInfo["autor"];
                  

                  echo "<div class='header'><p>Estas comprando curso:</p><h1>$courseName</h1></div><div class='subhead'><p>$nivel - $campo <br> Por: $autor</p></div><h4>Precio: $precio</h4>";
                  $saldoEnough = false;
                  if ($userdatassoc["saldo"] < $precio) {
                        echo "<h3 style='background-color: red; color: white;'>⚠️No tienes saldo disponible para adquirir el material</h3>";
                  }else {
                        $saldoEnough = true;
                  }
            }
            if ($saldoEnough) {
                  # Habilitar boton de compra
                  echo "<button style = \"color:white;background-color:black;\" onclick=\"document.cookie='buyResp=true'; window.location.reload()\" onmouseover=\"document.getElementById('notifier').innerHTML = 'Verifica que no dispones de acceso a lo que vas a comprar, para no pagar doble!'\">COMPRAR</button>";
                  if ($_COOKIE["buyResp"] == 'true') {
                        # iniciando compra
                        echo "Tramitando";
                  
                        # Inscribir propiedad, eliminar cookie y mandar al overview
                        if ($dbconn->query("INSERT INTO propiedadcurso (idCurso, idUsuario) VALUES ($idItem, $userId)") AND $dbconn->query("UPDATE users SET saldo = " . $userdatassoc["saldo"] - $precio . " WHERE id = $userId")) {
                              # code...
                              
                              echo "<script>document.cookie = 'buyResp=false'; window.location = 'courseoverview.php?idCourse=$idItem'</script>";
                        }else {
                              echo "<script>alert('Algo fue mal, presiona aceptar para abortar compra y salir sin error.'); doument.cookie='buyResp=false'; window.location = 'courseoverview.php?idCourse=$itemId'</script>";
                        }
                  }
            }

      }elseif ($itemType == "material") {
            # hacer select para el curso y id, ver precio, comparar con saldo y cargar la consulta y el overview
            $sqlSearchMaterial = "SELECT * FROM materiales WHERE idMaterial = $idItem";
            if ($materialquery = $dbconn->query($sqlSearchMaterial) AND $materialquery->num_rows == 1) {
                  $materialInfo = $materialquery->fetch_assoc();

                  $materialName = $materialInfo["nombre"];
                
                  
                  $precio = $materialInfo["precio"];
                  $descripcion = $materialInfo["descripcion"];
               
                  
          
                  

                  echo "<div class='header'><p>Estas comprando material:</p><h1>$materialName</h1></div><div class='subhead'><p>$descripcion</p></div><h4>Precio: $precio</h4>";
                  $saldoEnough = false;
                  if ($userdatassoc["saldo"] < $precio) {
                        echo "<h3 style='background-color: red; color: white;'>⚠️No tienes saldo disponible para adquirir el material</h3>";
                  }else {
                        $saldoEnough = true;
                  }
            }
            if ($saldoEnough) {
                  # Habilitar boton de compra
                  echo "<button style = \"color:white;background-color:black;\" onclick=\"document.cookie='buyResp=true'; window.location.reload()\" onmouseover=\"document.getElementById('notifier').innerHTML = 'Verifica que no dispones de acceso a lo que vas a comprar, para no pagar doble!'\">COMPRAR</button>";
                  if ($_COOKIE["buyResp"] == 'true') {
                        # iniciando compra
                        echo "Tramitando";
                  
                        # Inscribir propiedad, eliminar cookie y mandar al overview

                        if ($dbconn->query("INSERT INTO propiedadmaterial (idMaterial, idUsuario) VALUES ($idItem, $userId)") AND $dbconn->query("UPDATE users SET saldo = " . $userdatassoc["saldo"] - $precio . " WHERE id = $userId")) {
                              # code...
                              
                              echo "<script>document.cookie = 'buyResp=false'; window.location = 'materialoverview.php?idMat=$idItem'</script>";
                        }else {
                              echo "<script>alert('Algo fue mal, presiona aceptar para abortar compra y salir sin error.'); doument.cookie='buyResp=false'; window.location = 'courseoverview.php?idCourse=$itemId'</script>";
                        }
                  }
            }

      }else {
            echo "<h1>enlace incorrecto</h1>";
      }
      # generar titulo de propiedad

      # llevar al overview
}else{
      echo "<h2>Debes ingresar antes de comprar materiales</h2>";

}}else {
      echo "Error al buscar usuario";
}
      }else{
            echo "No pudimos acceder a la BD :<";
      }
      ?>
</body>
</html>