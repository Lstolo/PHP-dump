<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login - profesores</title>
</head>
<body>
         <script>
            document.cookie = "emailTeacher=" + window.prompt("Introduzca su dir. de correo");
            document.cookie =  "passTeacher=" + window.prompt("CLAVE");
            window.location = "landteachers.php";
            </script>
</body>
</html>