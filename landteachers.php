<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Profesores de Bina Academy</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">

            <?php
            if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
            $clave = $dbconn->real_escape_string($_COOKIE["passTeacher"]);
            $email = $dbconn->real_escape_string($_COOKIE["emailTeacher"]);

            # Login
            $dblogin = "SELECT * FROM profesores WHERE  Email = \"$email\" AND Clave = \"$clave\"";
            if ($loginquery = $dbconn->query($dblogin) AND $loginquery->num_rows > 0) {
                  # login exitoso
                  $userdata = $loginquery->fetch_assoc();
                  $userName = $userdata["Nombre"];
                  $userSaldo = $userdata["Saldo"];

                  echo "<div class='header'><h1>Hola, $userName</h1></div><div class='subheader'>Tu saldo es $userSaldo</div>";
                  
                  # Mostrar preguntas en un iframe, que deje responer
                  $sqlSearchQuestions = "SELECT preguntas.idPregunta, preguntas.preguntaTxt, cursos.NombreCusro FROM preguntas JOIN cursos ON preguntas.idCurso = cursos.idCurso WHERE estado = 'Pendiente' ORDER BY fecha_emision DESC";
                  if($quesion = $dbconn->query($sqlSearchQuestions)){
                        while ($questionAss = $quesion->fetch_assoc()) {
                              $text = $questionAss["preguntaTxt"];
                              $idPreg = $questionAss["idPregunta"];
                              $nom = $questionAss["NombreCusro"];
                              echo "<div class='materialShow' onclick='window.location=\"answerquestion.php?idQ=$idPreg\"'><h6>$nom</h6><p>$text</p></div>";


                        }
                  }else {
                        echo "ERROR AL BUSCAR PREGUNTAS";
                  }
            }else {
                  echo "<script>alert('Credenciales incorrectas'); window.location = 'loginTeachers.php'</script>";
            }
            }else{
                  echo "Conexion fallida";
            }
            ?>

    
      <?php?>
</body>
</html>