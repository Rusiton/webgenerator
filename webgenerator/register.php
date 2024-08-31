<?php

  echo "<h1>Registrarte es simple</h1>";

  session_start();
  if(isset($_SESSION['id'])){ // Comienza una sesión y verifica si el usuario ya está logeado
    header("Location:panel.php"); // Si el usuario ya está logeado, este es redireccionado a panel.php
  }

  if(isset($_POST['boton'])){

    if($_POST['password'] == $_POST['psw_confirm']){
      
      $connection = mysqli_connect("localhost", "adm_webgenerator", "webgenerator2024", "webgenerator");

      $email = filter_input(INPUT_POST, 'email');
      $password = filter_input(INPUT_POST, 'password');

      $query = "SELECT * FROM `usuarios` WHERE `email` = '$email'";
      $response = mysqli_query($connection, $query);

      if(mysqli_num_rows($response) < 1){
        
        $query = "SELECT `idUsuario` FROM `usuarios`";
        $response = mysqli_query($connection, $query);

        $date = date("Y-m-d");

        $query = "INSERT INTO `usuarios` (`email`, `password`, `fechaRegistro`) VALUES ('$email', '$password', '$date')";
        $response = mysqli_query($connection, $query);
        
        $query = "SELECT * FROM `usuarios` WHERE `email` = '$email'";
        $response = mysqli_query($connection, $query);

        $data = mysqli_fetch_array($response, MYSQLI_ASSOC);

        $_SESSION['id'] = $data['idUsuario'];
        header("Location:panel.php?register=true");


      } else{ echo "Correo electrónico ya en uso, intente con otro"; }

    } else{ echo "Las contraseñas no coinciden, intente de nuevo"; }

  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse es simple</title>
</head>
<body>
  <form name="register" method="post">
    <label for="email">Correo electrónico</label>
    <input type="text" name="email" required>
    <label for="password">Contraseña</label>
    <input type="text" name="password" required>
    <label for="psw_confirm">Confirmar Contraseña</label>
    <input type="text" name="psw_confirm" required>
    <input type="submit" name="boton" value="Registrarse">
  </form>
</body>
</html>