
    </head>
<?php
$var = array(3,5);
$search = info_usuario('tipo_user');
$codigo = info_usuario('codigo');
/** ALERTA PADRON MEDICO */
$msj = null;
$padron = false;
if($search == 3 && $codigo != 806)
{
    $msj = "'Favor de recordar al personal de visitas a su cargo, enviar el padrón de médicos actualizados.'";
    $padron = true;
}elseif ($search == 5)
{
    $msj = "'Favor de enviar su padrón de médicos actualizados.<br><i><b>Si ya envio y tiene conformidad por parte del Area de Sistemas, omita este mensaje.</b></i>'";
    $padron = true;
}else
{
    $padron = false;
}

/** ALERTA RUTEO */
$periodo = null;
$aviso = false;

$date = date('H:i');

$dia = (int)date('d');

$dif1 = 13 - $dia;
$dif2 = 25 - $dia;

if($dif1 <= 5 && $dif1 > 0)
{
    $periodo = 2;
    $aviso = true;
}
if($dif2 <= 5 && $dif2 > 0)
{
    $periodo = 1;
    $aviso = true;
}
// $aviso = true;
$padron = FALSE;//
#in_array($search, $var) && 
if($aviso == true && $padron == false){?>
    <body class="widescreen fixed-left-void" onload="advertencia_JS(<?php print $periodo;?>);">
    <?php }else if($padron == true && $aviso == false){ ?>
    <body class="widescreen fixed-left-void" onload="advertencia_JS_padron(<?php print $msj;?>);">
    <?php }else if($padron == true && $aviso == true){ ?>
    <body class="widescreen fixed-left-void" onload="advertencia_JS_2(<?php print $msj;?>,<?php print $periodo;?>);">
 <?php }else{ ?>
    <body class="widescreen fixed-left-void">
 <?php }?>
    <div id="loader"></div>
        <!-- Begin page -->
        <div id="wrapper" class="enlarged forced">
            <!-- Top Bar Start -->
            <div class="topbar">
                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <!-- Image Logo here -->
                        <a href="" class="logo">
                        <i class="icon-c-logo"> <img src="<?php print APP_PUBLIC; ?>public/assets/images/mks.png" height="42"/> </i>
                        <span><img src="<?php print APP_PUBLIC; ?>public/assets/images/mks.png" height="50"/></span>
                        </a>
                    </div>
                </div>
                
                <!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar-custom">
                
                    <ul class="list-inline float-right mb-0">          

                        <li class="list-inline-item dropdown notification-list">
                            <!-- <label class="text-white"><?php #print_r(info_usuario2()); ?></label> -->
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="<?php print APP_PUBLIC; ?>public/assets/images/Users/default_user.jpg" alt="user" class="rounded-circle">
                                <label for=""><?php print name_user_();?></label>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                                <!-- item-->
                                <!-- <div class="dropdown-item noti-title">
                                    <h5 class="text-overflow"><small>Welcome ! John</small> </h5>
                                </div> -->

                                <!-- item-->
                                <a href="Perfil" class=" dropdown-item notify-item">
                                    <i class="fa fa-user"></i> <span>Perfil</span>
                                </a>
                                <!-- item-->
                                <a href="<?php print web_path().'Logout';?>" class="dropdown-item notify-item">
                                    <i class="zmdi zmdi-power"></i> <span>Cerrar sesi&oacute;n</span>
                                </a>

                            </div>
                        </li>
                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="dripicons-menu"></i>
                            </button>
                        </li>
                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->