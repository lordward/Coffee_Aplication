<!DOCTYPE html>
<html lang="es">
<head>
<title>Coffee Aplication</title>
<meta charset="utf-8" />
<meta content="Carlos Valiente" name="author" />
<meta content="Sistema de gestión de cafés" name="description" />
<link rel="shortcut icon" href="favicon.ico" />

<!--Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--Import materialize.css-->
<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
<!--Let browser know website is optimized for mobile-->
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
  <body>

  <?php
  /* Recuperamos variables del formulario */
  session_start();
  $_SESSION["autenticado"]=false;
  $password =""; $validar="0";
  if (isset($_POST["password"])) {$password = $_POST["password"];}
  if (isset($_POST["validar"])) {$validar = $_POST["validar"];}
  if (($validar==1) && (strlen($password)>0)) {
       require('controlador/clsuser.php'); $usuario = new Usuario();
       if($usuario->leerFicha($password)==true){
                /*Usuario autenticado correctamente 
                Rellenamos las variables globales y saltamos al menú principal  */         
                $_SESSION["autenticado"]=true;
                $_SESSION["idusuario"]=$usuario->getidUsuario();
                $_SESSION["password"]=$password;
                if ($usuario->getTipo()=="NORMAL") {
                    $_SESSION["esadmin"]='NO';
                    echo "<script> window.location='/perfil.php'; </script>";
                }else{
                    $_SESSION["esadmin"]='SI';
                    echo "<script> window.location='/admin.php'; </script>";
                }
                exit();
       }else{
                echo '<div class="container"><div class="card-panel red lighten-2">Usuario inactivo o contraseña incorrecta</div></div>';
       }
  }
  ?>

    <!-- CABECERA -->
    <header>
      <div class="container">
        <h1>Coffee Aplication</h1>
        <img class="responsive-img" src="img/logo.png">
      </div>
    </header>
    

<!-- CUERPO PETICIÓN DE CONTRASEÑA -->
    <section>
      <div class="container">
         <div class="row">
            <div class="col s12">
                  <ul class="collapsible" data-collapsible="accordion">
                    <li><div class="collapsible-header active"><i class="material-icons">verified_user</i>Autenticación de usuario</div>
                      <div class="collapsible-body">
                            <form action="" method="post" style="padding: 20px;">
                                <label for="password">Contraseña:</label>
                                <input placeholder="Introduce tu contraseña" id="password" name="password" type="password" class="validate">
                                <input id="validar" name="validar" type="hidden" value="1">
                                <button class="btn waves-effect waves-light" type="submit" >Enviar
                                  <i class="material-icons left">send</i>
                                </button>
                            </form>
                      </div>
                    </li></ul>
            </div>
         </div>
      </div>
    </section>

    <!-- PIE -->
   <footer class="page-footer">
      <div class="footer-copyright">
        <div class="container">Coffee Aplication v.1.0.   <a href="coffee_aplication.apk"><img src="img/android.png" alt="Android"></a> </div> 
        
      </div>
    </footer>


    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
  </body>
</html>