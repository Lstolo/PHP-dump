<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Preguntas</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
      <div class="header"><h1>Preguntas a docentes</h1></div>
      <div class="subhead"><h1 onclick="window.location='questionEmiter.php'">+</h1></div>
      <?php
    ;
      # Login and show questions
      if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
            # Log in
            $userId = $dbconn->real_escape_string($_COOKIE["id"]);
            $password = $dbconn->real_escape_string($_COOKIE["pass"]);
          
            $dblogin = "SELECT id FROM users WHERE id = '$userId' AND clave = '$password'";
      
                        if ($loginquery = $dbconn->query($dblogin) AND $loginquery->num_rows > 0) {
                              # Buscar preguntas

                              $sqlSelectQuestions = "SELECT * FROM preguntas WHERE idUsuario = $userId";
                              if ($questionQuery = $dbconn->query($sqlSelectQuestions)) {
                                    if ($questionQuery->num_rows > 0) {

                                          function eliminarPregunta($idPregunta){
                                                      
                                          }


                                          echo "<h4>Si tu pregunta sigue pendiente por un tiempo superior a una semana, se elimina y se devuelven los puntos</h4>";
                                          while ($questionAssoc = $questionQuery->fetch_assoc()) {
                                                # Mostrar la pregunta
                                                $preguntaID = $questionAssoc["idPregunta"];
                                                $respuesta = "";
                                                $estado = $questionAssoc["estado"];
                                                $profe = "";
                                                $pregunta = $questionAssoc["preguntaTxt"];
                                                $fecha = $questionAssoc["fecha_emision"];

                                                $currentTime = time();
                                                $emissionTime = strtotime($fecha);
                                               
                                                if ($currentTime > ($emissionTime + 60*60*170) AND ( $estado == "Pendiente" OR $estado == "Rechazada")) {
                                                      eliminarPregunta($preguntaID);
                                                }
                                                if ($estado == "Resuelto") {
                                                      $respuesta = $questionAssoc["respuestaTxt"];
                                                      $profe =  $questionAssoc["docente"];
                                                }


                                               
                                                
                                                echo "<div><h4>$pregunta</h4><h5>$estado - $fecha</h5> <p>$respuesta</p> <p>$profe</p></div>";
                                          }
                                    }else {
                                          echo "<p>No has hecho ninguna pregunta. Apreta + para formular.</p>";
                                    }
                              }else {
                                    echo "No hemos podido recuperar tus preguntas";
                              }

                        }else {
                              echo "<script>window.location = 'panel.php'</script>";
                        }
                  }else {
                        echo "Fallo la BD :<";
                  }
      ?>
</body>
</html>