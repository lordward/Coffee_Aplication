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
  /*Control de seguridad*/
  session_start();
  if ($_SESSION["autenticado"]==false){echo "<script>window.location='/';</script>";}

  /*Leemos la ficha de usuario*/
  require_once('controlador/clsuser.php'); $usuario = new Usuario();
  $usuario->leerFicha($_SESSION["password"]);

  /*Recupero información sobre este usuario*/
  $num_cafes = $usuario->contarCafes($usuario->getidUsuario()); //numero de cafes totales
  $num_cafes_pagados = $usuario->contarCafesPagados($usuario->getidUsuario()); //numero de cafes pagados
  $r_cafes = $usuario->leerCafes($usuario->getidUsuario());
  $r_cafes_pagados = $usuario->leerCafesPagados($usuario->getidUsuario());
  ?>

    <!-- CABECERA -->
    <header>
      <div class="container">
        <h1><?php echo $usuario->getNombre(); ?></h1>
        <?php echo '<img style="margin-left:45px;" width="150px" class="responsive-img" src="img/users/'. $usuario->getFoto() .'">';   ?>
      </div>
    </header>
    

<!-- CUERPO PETICIÓN DE CONTRASEÑA -->
    <section>
      <div class="container">
         <div class="row">
            <div class="col s12">
                 <ul class="collapsible popout" data-collapsible="accordion">
                  <li>
                    <div class="collapsible-header"><i class="material-icons">input</i>Registrar cafés</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                        
                      <!-- REGISTRAR CAFES -->
                      <form action="controlador/nuevo_cafe.php" method="post">
                        <label for="fecha">Fecha:</label>
                        <input id="fecha" name="fecha" type="date" value ="<?php echo date("Y-m-d");?>" class="validate">
                        <label for="cantidad">Cantidad:</label>
                        <input id="cantidad" value="1" name="cantidad" type="number" class="validate">
                        <button class="btn waves-effect waves-light" type="submit" >Añadir café
                          <i class="material-icons left">send</i>
                        </button>
                      </form>
                      <!-- ----------------- -->

                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header"><i class="material-icons">list</i>Mis últimos cafés</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                    
                          <!-- MIS ULTIMOS CAFES -->
                            <table class="striped">
                            <thead>
                              <tr>
                                  <th data-field="fecha">Fecha</th>
                                  <th data-field="cafes">Cafés</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($obj = $r_cafes->fetch_object()) {
                                        echo "<tr>";
                                        echo "<td>".$obj->fecha."</td>";
                                        echo "<td>".$obj->cantidad."</td>";
                                        echo "</tr>";
                                }
                                ?>
                            </tbody>
                            </table>
                          <!-- ----------------- -->

                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header"><i class="material-icons">payment</i>Balance de pagos</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                      
                      <!-- BALANCE DE PAGOS -->
                      <span style ="color:red;">Cafés tomados: <strong><?php echo $num_cafes.'</strong> --> (Total: '. $num_cafes *0.5 .'€)'; ?></span> <br>
                      <span style ="color:green;">Cafés pagados: <strong><?php echo $num_cafes_pagados.'</strong> --> (Total: '. $num_cafes_pagados *0.5 .'€)'; ?></span> <br> <hr>
                      <?php 
                      if ($num_cafes_pagados < $num_cafes){
                        echo '<strong style ="color:red;">Debes: '. ($num_cafes - $num_cafes_pagados) .' cafés. Total: '. (($num_cafes - $num_cafes_pagados) * 0.50) . ' € </strong>';   
                      }else{
                        echo '<strong style ="color:green;">Bonus ' . ($num_cafes_pagados - $num_cafes) . ' cafés</strong>';
                      }
                      ?><hr>
                      <?php 
                      if ($r_cafes_pagados->num_rows>0){  ?> 
                          <table class="striped">
                          <thead>
                            <tr>
                                <th data-field="pagado">Pagado</th>
                                <th data-field="cafes2">Cafés</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php
                              while ($obj = $r_cafes_pagados->fetch_object()) {
                                      echo "<tr>";
                                      echo "<td>".$obj->fecha."</td>";
                                      $valor = $obj->cafes;
                                      echo "<td>(". ($valor / 2) .'€) - '.$obj->cafes.' cafés</td>';
                                      echo "</tr>";
                              }
                              ?>
                          </tbody>
                          </table>
                      <?php } ?>
                      <!-- ---------------- -->

                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header"><i class="material-icons">assessment</i>Estadísticas</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                        <div><img src="img/negro.png" alt="Negro"> Total cafés <img style="margin-left:15px;" src="img/rojo.png" alt="Rojo"> Euros al mes</div>
                        <!-- - ESTADISTICAS - -->
                        <?php  
                        $_SESSION["datay"] = array();$_SESSION["euros"] = array(); $_SESSION["labels"] = array(); $i=0; $_SESSION["num_cafes"] = $num_cafes;
                        $estadisticas = $usuario->leerEstadisticas($usuario->getidUsuario());
                        while ($obj = $estadisticas->fetch_object()) {
                            $_SESSION["datay"][$i]  = $obj->sumacantidad;
                            $_SESSION["labels"][$i] = $obj->fecha;
                            $_SESSION["euros"][$i] = $obj->sumacantidad *0.5;
                            $i += 1;
                        }
                        ?>  
                        <img src="graphic.php" alt="" border="0">
                        <!-- ---------------- -->

                    </div>
                  </li>
                </ul>
                <a href="controlador/cerrar_sesion.php" class="waves-effect waves-light btn"><i class="material-icons left">input</i>Cerrar sesión</a>
            </div>
         </div>
      </div>
    </section>

    <!-- PIE -->
   <footer class="page-footer">
      <div class="footer-copyright">
        <div class="container">Coffee Aplication v.1.0. <a href="coffee_aplication.apk"><img src="img/android.png" alt="Android"></a> </div> 
      </div>
    </footer>


    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
  </body>
</html>