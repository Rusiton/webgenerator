<?php

  echo "<h1>Bienvenido a tu panel</h1>";
  
  session_start();

  
  if(!isset($_SESSION['id'])){
    header("Location:login.php");
    exit();
  }

  $userID = $_SESSION['id'];
  
  $connection = mysqli_connect("localhost", "adm_webgenerator", "webgenerator2024", "webgenerator");
  
  if(isset($_GET['download'])){

    $downloadParam = $_GET['download'];

    shell_exec("zip -r $downloadParam $downloadParam");

    header("Location: $downloadParam.zip");

  }

  if(isset($_POST['delete'])){

    $deleteParam = $_POST['domain'];
    
    shell_exec("rm -r $deleteParam");
    shell_exec("rm $deleteParam.zip");

    $query = "DELETE FROM webs WHERE dominio = '$deleteParam'";

    mysqli_query($connection, $query);

  }

  if(isset($_POST['boton'])){

    $webName = $_POST['webName'];
    $domain = $userID.$webName;

    $query = "SELECT dominio FROM webs WHERE dominio = '$domain'";

    $response = mysqli_query($connection, $query);

    if(mysqli_num_rows($response) < 1){
      
      $date = date("Y-m-d");

      $query = "INSERT INTO webs (idUsuario, dominio, fechaCreacion) VALUES ('$userID', '$domain', '$date')";      

      mysqli_query($connection, $query);

      echo shell_exec("../wix.sh $domain");

    }
    else{ echo "<p style='color:red;'>Ese dominio ya existe!</p>"; }

  }

  $query = "SELECT dominio FROM webs WHERE idUsuario = '$userID'";

  $response = mysqli_query($connection, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a tu panel</title>
</head>
<body>
  <a href='logout.php '>Cerrar sesión de <?php echo $userID; ?></a>
  <br><br>
  <form action="panel.php" method="post">
    <label for="text">Generar web de:</label>
    <input type="text" name="webName">
    <input type="submit" name="boton" value="Crear Web">
  </form>

  <?php

    if(mysqli_num_rows($response) > 0){

      while($row = mysqli_fetch_array($response, MYSQLI_ASSOC)){

        $currentDomain = $row['dominio'];

        echo "<li style='height: 20px; display: flex; gap: 10px;'><a href='$currentDomain'>$currentDomain</a> <a href='?download=$currentDomain'>Descargar Web</a><form method='post'><input type='hidden' name='domain' value='$currentDomain'><input type='submit' name='delete' value='Eliminar' style='border: none; background: none; padding: 0; color: rgb(0, 0, 238); text-decoration: underline; font-family: Times New Roman; font-size: 1em; cursor: pointer;'></form></li>";

      }

      echo "<br>";

    }

    if($_SESSION['id'] == 1){

      $query = "SELECT dominio FROM webs";

      $response = mysqli_query($connection, $query);

      if(mysqli_num_rows($response) > 0){

        while($row = mysqli_fetch_array($response, MYSQLI_ASSOC)){

          $currentDomain = $row['dominio'];

          echo "<li style='height: 20px; display: flex; gap: 10px;'><a href='$currentDomain'>$currentDomain</a> <a href='?download=$currentDomain'>Descargar Web</a><form method='post'><input type='hidden' name='domain' value='$currentDomain'><input type='submit' name='delete' value='Eliminar' style='border: none; background: none; padding: 0; color: rgb(0, 0, 238); text-decoration: underline; font-family: Times New Roman; font-size: 1em; cursor: pointer;'></form></li>";

        }

      }

    }

  ?>

</body>
</html>