<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Responder preguntas</title>
</head>
<body>

<link rel="stylesheet" href="genstyle.css">

<?php
$idPreg = $_GET["idQ"];

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
      $userSurame = $userdata["Apellido"];
      $userId = $userdata["idProfesor"];
      echo "<div class='header'><h1>$userName, nos encanta que ayude a la comunidad.</h1></div>";
      
      # Mostrar preguntas en un iframe, que deje responer
      $sqlSearchQuestions = "SELECT preguntas.idPregunta, preguntas.preguntaTxt, cursos.NombreCusro, cursos.precio_pregunta FROM preguntas JOIN cursos ON preguntas.idCurso = cursos.idCurso WHERE preguntas.idPregunta =$idPreg";
      if($quesion = $dbconn->query($sqlSearchQuestions)){
            while ($questionAss = $quesion->fetch_assoc()) {
                  $text = $questionAss["preguntaTxt"];
                  $idPreg = $questionAss["idPregunta"];
                  $nom = $questionAss["NombreCusro"];
                  $precio = $questionAss["precio_pregunta"];
                  echo "<div class='materialShow'><h6>$nom</h6><p>$text</p><h4>Si bien al responder, va a recibir $precio puntos, si responde mal a drede, puede ser reportado y eliminado de la plataforma.</h4></div>";
                  echo "<form action=\"answerquestion.php?idQ=$idPreg\" method=\"post\" id =\"sendform\"></form>";
                  echo "<script>document.getElementById('sendform').innerHTML = \"<textarea name='respuesta' id='resp' placeholder='5000 caracteres max.'></textarea><input type='submit' name='submit' value ='📩'></input>\";</script>";
# Hacer metodo que esuche el post y que responda
 $respuestaPreg = $dbconn->real_escape_string($_POST["respuesta"]);
 if (isset($_POST["submit"]) AND isset($respuestaPreg)) {
      # code...
      echo "enviando respuesta";
      # update a las preguntas y update al saldo

      $sqlUpdateQuestion = "UPDATE preguntas SET estado = 'RESUELTO', respuestaTxt = '$respuestaPreg', idDocente = $userId, docente = '$userName $userSurname' WHERE idPregunta = $idPreg";
      $sqlUpdateSaldo = "UPDATE profesores SET saldo = " . ($saldo + $precio) . " WHERE idProfesor = $userId";
      if ($dbconn->query($sqlUpdateQuestion) AND $dbconn->query($sqlUpdateSaldo)) {
            echo "<script> alert('Muchas gracias, tu respuesta ha sido transferida.'); window.location ='landteachers.php';</script>";
      }
 }
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