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
  if ($_SESSION["esadmin"]=='NO'){echo "<script>window.location='/';</script>";}

  /*Leemos la ficha de usuario*/
  require_once('controlador/clsuser.php'); $usuario = new Usuario();
  $usuario->leerFicha($_SESSION["password"]);
  $lista_usuarios = $usuario->listaUsuarios();
  $lista_pago_cafes = $usuario->listaPagoCafes();
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
                    <div class="collapsible-header"><i class="material-icons">input</i>Cobrar cafés</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                        
                      <!-- REGISTRAR CAFES -->
                      <form action="controlador/nuevo_pago_cafe.php" method="post">
                        <label for="id_usuario">Seleccione usuario:</label>
                        <select class ="icons" id="id_usuario" name="id_usuario">
                          <?php while ($obj = $lista_usuarios->fetch_object()) {
                                echo '<option value="'.$obj->Id.'">'.$obj->nombre.'</option>';
                          }?>
                        </select>
                        <label for="fecha">Fecha:</label>
                        <input id="fecha" name="fecha" type="date" value ="<?php echo date("Y-m-d");?>" class="validate">
                        <label for="cantidad">¿Cuantos cafés cobramos?:</label>
                        <input id="cafes" value="1" name="cafes" type="number" class="validate">
                        <button class="btn waves-effect waves-light" type="submit" >Cobrar cafés
                          <i class="material-icons left">send</i>
                        </button>
                      </form>
                      <!-- ----------------- -->

                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header"><i class="material-icons">list</i>Balance de cobros</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                    
                          <!-- MIS ULTIMOS CAFES -->
                            <table class="striped">
                            <thead>
                              <tr>
                                  <th data-field="usuario">Usuario</th>
                                  <th data-field="fecha">Fecha</th>
                                  <th data-field="num">Nª</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($obj = $lista_pago_cafes->fetch_object()) {
                                        echo "<tr>";
                                        echo "<td>".$obj->nombre."</td>";
                                        echo "<td>".$obj->fecha."</td>";
                                        echo "<td>".$obj->cafes."</td>";
                                        echo "</tr>";
                                }
                                ?>
                            </tbody>
                            </table>
                          <!-- ----------------- -->

                    </div>
                  </li>
                  <li>
                    <div class="collapsible-header"><i class="material-icons">payment</i>Cafés pendientes</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                    
                            <!-- CAFES PENDIENTES -->
                            <table class="striped">
                            <thead>
                              <tr>
                                  <th data-field="usuario">Usuario</th>
                                  <th data-field="fecha">Cafés</th>
                              </tr>
                            </thead>

                            <?php $lista_usuarios = $usuario->listaUsuarios(); ?>

                            <tbody>
                                <?php
                                while ($obj = $lista_usuarios->fetch_object()) {
                                    $num_cafes = $usuario->contarCafes($obj->Id);
                                    $num_cafes_pagados = $usuario->contarCafesPagados($obj->Id);

                                        echo "<tr>";
                                        echo "<td>".$obj->nombre."</td>";
                                        if ($num_cafes_pagados < $num_cafes){
                                             echo '<td> <strong style="color:red;"> ('. abs(($num_cafes_pagados - $num_cafes)/ 2) .'€) - ' . abs($num_cafes_pagados - $num_cafes) .' cafés </strong> </td>';
                                        }else{
                                             echo '<td> <strong style="color:green;"> ('. (($num_cafes_pagados - $num_cafes)/2) .'€) - '.($num_cafes_pagados - $num_cafes) . ' cafés </strong> </td>';
                                        }       
                                        echo "</tr>";
                                }
                                ?>
                            </tbody>
                            </table>
                          <!-- ----------------- -->

                    </div>
                  </li>
                   <li>
                    <div class="collapsible-header"><i class="material-icons">assessment</i>Estadísticas</div>
                    <div class="collapsible-body" style="padding: 10px 20px;">
                        <div><img src="img/negro.png" alt="Negro"> Total cafés <img style="margin-left:15px;" src="img/rojo.png" alt="Rojo"> Euros <img style="margin-left:15px;" src="img/verde.png" alt="Rojo"> Usuarios</div>
                        <!-- - ESTADISTICAS - -->
                        <?php  
                        $_SESSION["euros"] = array(); $_SESSION["consumo"] = array(); $_SESSION["usuarios"] = array(); $_SESSION["labels"] = array(); $i=0;$num_cafes=0;
                        $estadisticas = $usuario->leerEstadisticas(0);
                        while ($obj = $estadisticas->fetch_object()) {
                            $_SESSION["consumo"][$i]  = $obj->sumacantidad;
                            $_SESSION["euros"][$i]  = $obj->sumacantidad * 0.5;
                            $_SESSION["usuarios"][$i] = $obj->numusuarios;
                            $_SESSION["labels"][$i]   = $obj->fecha;
                            $i += 1;
                            $num_cafes += $obj->sumacantidad;
                        }
                        $_SESSION["num_cafes"] = $num_cafes;
                        ?>  
                        <img src="graphic_admin.php" alt="" border="0">
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