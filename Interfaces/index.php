<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="stylesheet" href="login/css2/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="login/css2/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <!-- <link rel="stylesheet" href="css/all.min.css"> -->
   <!-- <link rel="stylesheet" href="css/fontawesome.min.css"> -->
   <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
   <title>Inicio de sesión</title>
</head>

<body>
   <img class="wave" src="login/img2/wave.png">
   <div class="container">
      <div class="img">
         <img src="login/img2/bg.svg">
      </div>
      <div class="login-content">
         <form method="post" action="">
            <img src="login/img2/avatar.svg">
            <h2 class="title">BIENVENIDO</h2>
            <?php
            include("login/modelo2/controlador.php");
            ?>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Usuario</h5>
                  <input id="usuario" type="text" class="input" name="usuario">
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="input" class="input" name="password">
               </div>
            </div>
            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>

            <div class="text-center">
            </div>
            <input name="btningresar" class="btn" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>
   <script src="login/js2/fontawesome.js"></script>
   <script src="login/js2/main.js"></script>
   <script src="login/js2/main2.js"></script>
   <script src="login/js2/jquery.min.js"></script>
   <script src="login/js2/bootstrap.js"></script>
   <script src="login/js2/bootstrap.bundle.js"></script>

</body>

</html>