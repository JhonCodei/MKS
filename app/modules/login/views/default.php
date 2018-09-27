<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php print APP_ICON; ?>
    <title>Login</title>
    <link rel="stylesheet" href="<?php print APP_PUBLIC;?>public/assets/css/login.css" />
    <link rel="stylesheet" href="<?php print APP_PUBLIC;?>public/assets/css/loader.css" />
    <script src="<?php print APP_PUBLIC;?>app/scripts/__.js"></script>    
</head>
<body>
<div id="loader"></div>
<div class="wrapper fadeInDown" >
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Iniciar Sesi&oacute;n </h2>
    <br><br>
    <!-- Login Form -->
    <div onkeypress="return keylogeo(event);">
      <div id="error"></div>
      <input type="text" id="usuario" class="fadeIn second" placeholder="Usuario">
      <br>
      <input type="password" id="password" class="fadeIn third" placeholder="Contrase&ntilde;a">
      <br><br>
      <button type="button" class="btn btn-primary fadeIn fourth" onclick="return logeo();">Iniciar</button>
      <br><br>
    </div>

    <!-- Remind Passowrd -->
    <!-- <div id="formFooter">
      <a class="underlineHover" href="#">Forgot Password?</a>
    </div> -->

  </div>
</div>
<script src="<?php print APP_PUBLIC;?>public/assets/js/jquery.min.js"></script>
<script src="<?php print web_path();?>app/modules/login/script.js"></script>
</body>
</html>