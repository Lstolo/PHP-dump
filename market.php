<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Mercado - binaAcademy</title>
</head>
<body>
      <link rel="stylesheet" href="genstyle.css">
      <div class="header"><h1>Explora todos nuestros cursos</h1></div>
      <div class="subhead"><p>Puedes clickear en un curso, para ver los materiales dentro.</p></div>
      <?php
   # Conectar con la BD 

if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
      # Mostrar cursos
      if ($cuoursequery = $dbconn->query("SELECT * FROM cursos")) {
            while ($courseassoc = $cuoursequery->fetch_assoc()) {
                  echo "
                  <div class='materialShow' onclick = \"window.location = 'courseoverview.php?idCourse=" . $courseassoc["idCurso"] . "'\">
                  <h4>" . $courseassoc["Campo"] . " - " . $courseassoc["Nivel"] . "</h4>
                  <h2>" . $courseassoc["NombreCusro"] . "</h2>
                  <p>Por: " . $courseassoc["autor"] . ". Precio " . $courseassoc["Precio"] . "</p>
                  </div>
                  ";
            }
      }else {
            echo "No pudimos recuperar los cursos :(";
      }
}else {
      echo "Enlace fallido con la BD :(";
}
      ?>
</body>
</html>