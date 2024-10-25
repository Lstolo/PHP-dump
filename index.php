<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>BinaAcademy</title>
</head>
<link rel="stylesheet" href="genstyle.css">
<body>
   <div class="header"><h1>¡Bienvenidos a BinaAcademy!</h1>   </div>
   
   <h2>Crear cuenta</h2>
   <form action="index.php" method="post" id="fsup">
      <p>Nombre</p>
      <input type="text" name="namesup" id="">
      <p>Apellido</p>
      <input type="text" name="surnamesup" id="">
      <p>Correo</p>
      <input type="mail" name="mailsup" id="">
      <p>Clave</p>
      <input type="password" name="passw1sup" id="">
      <p>Repetir clave</p>
    
      <input type="password" name="passw2sup" id="">
  <br>
<input type="submit" value="Registrar" name="subsup">
      
   </form>
   <h2>Ingresar</h2>
   <form action="index.php" method="post" id="fin" >
   <p>Correo</p>
      <input type="mail" name="mailsin" id="">
      <p>Clave</p>
      <input type="password" name="passwsin" id="">
   
  <br>
<input type="submit" value="Ingresar" name="subsin">
      
   </form>
   <?php
 
   if ($dbconn = new mysqli("localhost", "root", "", "bina")) {
      #Registrar todos los campos
      $idCookie =  $dbconn->real_escape_string($_COOKIE["id"]);
      $passwCookie = $dbconn->real_escape_string($_COOKIE["passw"]);
      $mailSup = $dbconn->real_escape_string($_POST["mailsup"]);
      $pass1Sup = $dbconn->real_escape_string($_POST["passw1sup"]);
      $pass2Sup = $dbconn->real_escape_string($_POST["passw2sup"]);
      $nameSup = $dbconn->real_escape_string($_POST["namesup"]);
      $surnameSup = $dbconn->real_escape_string($_POST["surnamesup"]);
     
      $mailSin = $dbconn->real_escape_string($_POST["mailsin"]);
      $passwSin = $dbconn->real_escape_string($_POST["passwsin"]);
      $subsin = $dbconn->real_escape_string($_POST["subsin"]);
      $subsup = $dbconn->real_escape_string($_POST["subsup"]);

      #actuar segun accion
      if (isset($subsin)) {
         echo "logging in";
         #Buscar segun mail y clave
         $dblogin = "SELECT id FROM users WHERE mail = '$mailSin' AND clave = '$passwSin'";
         if ($loginquery = $dbconn->query($dblogin)) {
            if ($loginquery->num_rows > 0) {
             
               $userid = $loginquery->fetch_assoc()["id"];
               echo "<script>document.cookie = 'id=".$userid."'; document.cookie = 'pass=$passwSin';
            window.location = \"panel.php\"
               </script>";
               
            }else {
               echo "No tenemos usuarios con las credenciales introducidas";
            }
         }else {
            echo "fracaso al consultar nuestra base de datos";
         }

      }elseif (isset($subsup)) {
echo "signing up";
         #chequear que todos los campos necesarios sean validos
       
            #Chequear que no exista el mail
         $query = "SELECT nombre FROM users WHERE mail = \"$mailSup\"";
         if ($mailquery = $dbconn->query($query)) {
            echo "validando mail";
            if ($mailquery->num_rows > 0) {
               echo "<p>Hola " . $mailquery->fetch_assoc()["nombre"] . ", ya tienes una cuenta, prueba iniciando sesion</p>";
            }else{
               echo 'email no registrado';
               #preparar insercion
               $dbSignup = "INSERT INTO `users`(`nombre`, `apellido`, `mail`, `clave`) VALUES ('$nameSup','$surnameSup','$mailSup','$pass1Sup')";
               if ($dbconn->query($dbSignup)) {
                  echo "alta exitosa";
                  # pedir id y plantar cookie
                  $dblogin = "SELECT id FROM users WHERE mail = '$mailSup' AND clave = '$pass1Sup'";
                  if ($loginquery = $dbconn->query($dblogin)) {
                     if ($loginquery->num_rows > 0) {
                      
                        $userid = $loginquery->fetch_assoc()["id"];
                        echo "<script>document.cookie = 'id=".$userid."'; document.cookie = 'pass=$pass2Sup';
                        
                        </script>";
                        echo "<script>
                        window.location = \"panel.php\"
                           </script>";
                     }else {
                        echo "Error 002";
                     }

                  
               }}else {
                  echo "alta fallida";
               }
            }

         }else {
            # notificar error en paso 1, dar cod. 0001
         }
         
         
         
      }else {
         echo "do sth";
      }
      
   }else {
      echo "<p>No se pudo acceder a la base de datos :(</p>";
   }
   
   ?>
</body>
</html>