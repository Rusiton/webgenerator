<?php

  echo "<h1>webgenerator Santiago Galasso</h1>";

  session_start(); 

  if(isset($_SESSION['id'])){ // Comienza una sesión y verifica si el usuario ya está logeado
    header("Location:panel.php"); // Si el usuario ya está logeado, este es redireccionado a panel.php
  }

  if(isset($_POST['boton'])){
    
    $connection = mysqli_connect("localhost", "adm_webgenerator", "webgenerator2024", "webgenerator");

    $email = filter_input(INPUT_POST, 'email');
    
    $query = "SELECT * FROM `usuarios` WHERE `email` = '$email'";
    $response = mysqli_query($connection, $query);

    if(mysqli_num_rows($response) > 0){ // Comprueba si el correo existe

      $row = mysqli_fetch_array($response, MYSQLI_ASSOC);

      $password = filter_input(INPUT_POST, 'password');

      if($row['password'] == $password){ // Comprueba si la contraseña es correcta
        
          $_SESSION['id'] = $row['idUsuario'];
          header("Location:panel.php"); // Si el usuario no estaba logeado, su ID de usuario se guarda en $_SESSION

      } else{ echo "<p style='color: red;'>Contraseña incorrecta</p>"; }

    } else{ echo "<p style='color: red;'>Correo electrónico inválido</p>"; }

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>webgenerator Santiago Galasso</title>
</head>
<body>
  <form name="login" method="post">
    <input type="text" name="email" required>
    <input type="text" name="password" required>
    <a href="register.php">Registrarse</a>
    <input type="submit" name="boton" value="Ingresar">
  </form>
</body>
</html>