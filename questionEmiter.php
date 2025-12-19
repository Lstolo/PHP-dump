<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>BinaAcademy - preguntar</title>
</head>
<body>
     <link rel="stylesheet" href="genstyle.css">
       <div class="header"><h1>Preguntar al docente</h1></div>
       <div class="subhead"><h2>Llena el formulario para hacer la pregunta</h2></div>
       <form action="questionEmiter.php" method="post" id="form">
            <p>Elegir curso</p>
            <select name="selectorCourse" id="selectcourse"></select>
            <br>
       </form>
      <?php
      # Hacer login y sacar saldo
      

      # Conectar con BD
      if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
            $userId = $dbconn->real_escape_string($_COOKIE["id"]);
            $password = $dbconn->real_escape_string($_COOKIE["pass"]);
          
            $dblogin = "SELECT id, saldo FROM users WHERE id = '$userId' AND clave = '$password'";
      
                        if ($loginquery = $dbconn->query($dblogin) AND $loginquery->num_rows > 0) {
                          $userSaldo = $loginquery->fetch_assoc()["saldo"];
      # Buscar todos los cursos: id, nombre, nivel, campo, precio de pregunta
                              $sqlCourses = "SELECT idCurso, NombreCusro, Nivel, Campo, precio_pregunta FROM cursos";
                              if ($courseSelectionquery = $dbconn->query($sqlCourses)) {
                                    if ($courseSelectionquery->num_rows > 0) {
                                          # generar formulario
                                          echo "<script>document.getElementById('form').innerHTML += \"<textarea name='pregunta' placeholder='Escribe tu pregunta... (max. 5000 caracteres)'></textarea> <br>\";</script>";

                                          while ($courseassoc = $courseSelectionquery->fetch_assoc()){ 
                                                echo "<script>document.getElementById('selectcourse').innerHTML += \"<option value='" . $courseassoc["idCurso"] . "'>" . $courseassoc["NombreCusro"] . " - " . $courseassoc["Campo"] . " | " . $courseassoc["Nivel"] . " - costo: " . $courseassoc["precio_pregunta"] . "</option>\";</script>";
                                          }
                                          echo "<script>document.getElementById('form').innerHTML += \"<input name='send' type='submit' value='Tramitar'></input>\"</script>";
                                          # responder al formularuio

  
                                          if (isset($_POST["send"])) {
                                                $preguntaTxt = $dbconn->real_escape_string($_POST["pregunta"]);
                                                $courseId = $_POST["selectorCourse"];

                                                # Verificar precio de pregunta, comparar con saldo y crear
                                                $sqlGetQuestionPrice ="SELECT precio_pregunta FROM cursos WHERE idCurso = $courseId";
                               
                                                if ($precioPreguntass = $dbconn->query($sqlGetQuestionPrice)) {
                                                      $precioPregunta = $precioPreguntass->fetch_assoc()["precio_pregunta"];
                                                      if ($userSaldo >= $precioPregunta) {
                                                            # Generar pregunta y cambiar el saldo

                                                            $sqlRegisterQuestion = "INSERT INTO `preguntas`(`idUsuario`, `idCurso`, `preguntaTxt`, `estado`) VALUES ($userId,$courseId,\"$preguntaTxt\",'Pendiente')";
                                                            $sqlSubstractSaldo = "UPDATE users SET saldo = " . $userSaldo - $precioPregunta . " WHERE id = $userId";
                                                            
                                                            if ($dbconn->query($sqlRegisterQuestion) AND $dbconn->query($sqlSubstractSaldo)) {
                                                                  # code...
                                                                  echo "<script>window.location = 'questionPanel.php'</script>";
                                                            }else {
                                                                  
                                                                  echo "Fallamos al cobro y/o pubilicacion de pregunta :<";
                                                            }


                                                      }else {
                                                            echo "<h3 style='background-color: red; color: white;'>⚠️No tienes saldo disponible para preguntar</h3>";
                                                      }
                                                }else {
                                                      
                                                      echo "algo salio mal. err 1/qemiter;";
                                                }


                                          }
                                    }else {
                                          
                                          echo "No hemos podido encontrar cursos para que hagas preguntas";
                                    }

                              }else {
                                    echo "ERROR AL RECUPERAR CURSOS";
                              }
    
                           }else {
                              echo "<script>window.location = 'index.php'</script>";
                           }
                        }else {
                              echo "No se pudo acceder a la BD :(";
                           }
      ?>
</body>
</html>