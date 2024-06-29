<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>LUXXO</title>


    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="css\bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="css\nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="css\demo\nifty-demo-icons.min.css" rel="stylesheet">




    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="plugins\pace\pace.min.css" rel="stylesheet">
    <script src="plugins\pace\pace.min.js"></script>


    <!--Demo [ DEMONSTRATION ]-->
    <link href="css\demo\nifty-demo.min.css" rel="stylesheet">

        
</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright slide mainnav-out navbar-fixed">
        
        <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">

                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href='vistaInicio' class="navbar-brand">
                        <img src="img\logo.png" alt="Nifty Logo" class="brand-icon">
                        <div class="brand-title">
                            <span class="brand-text">LUXXO</span>
                        </div>
                    </a>
                </div>
                <!--================================-->
                <!--End brand logo & name-->


                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">

                        <!--Navigation toogle button-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="#">
                                <i class="demo-pli-list-view"></i>
                            </a>
                        </li>
   

                    </ul>
                    <ul class="nav navbar-top-links">
                        <?php 
                            foreach ($permisosVistas as $vistaP) {
                             
                           
                            ?>

                        @can($vistaP['name'])
                        <li id="" class="dropdown">
                            <a href="{{ $vistaP['ruta'] }}" data-tooltip="{{ $vistaP['name'] }}"
                                class="btn-profesional dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i style="font-size:21px" class="{{ $vistaP['icono'] }}"></i>

                                </span>
                            </a>
                        </li>
                        @endcan

                        <?php 
                    } ?>

                       

                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-tooltip="Perfil" data-toggle="dropdown"
                                class="btn-profesional dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                    <!--You can use an image instead of an icon.-->
                                    <!--<img class="img-circle img-user media-object" src="plantillaNuevo\img/profile-photos/1.png" alt="Profile Picture">-->
                                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                    <i style="font-size:21px" class="fa-solid fa-user"></i>

                                </span>
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <!--You can also display a user name in the navbar.-->
                                <!--<div class="username hidden-xs">Aaron Chavez</div>-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                            </a>


                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="perfilD"><i class="demo-pli-male icon-lg icon-fw"></i> Perfil</a>
                                    </li>
                                    <li>
                                        <a href="logout"><i class="demo-pli-unlock icon-lg icon-fw"></i>
                                            Salir</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--================================-->
                <!--End Navbar Dropdown-->

            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    

                    </div>

                
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    
					
					
				
					
					
					    
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->


            
            <!--ASIDE-->
 
            <!--===================================================-->
            <!--END ASIDE-->

            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">





                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">

                                <!--Profile Widget-->



                                <!--Shortcut buttons-->
                                <!--================================-->

                                <!--================================-->
                                <!--End shortcut buttons-->
                                <div id="mainnav-profile" class="mainnav-profile">
                                    <div class="profile-wrap text-center">
                                        <div class="pad-btm">
                                            <img class="img-circle img-md"
                                                src="plantillaNuevo\img\profile-photos\1.png" alt="Profile Picture">
                                        </div>
                                        <a href="#profile-nav" class="box-block" data-toggle="collapse"
                                            aria-expanded="false">
                                            <span class="pull-right dropdown-toggle">
                                                <i class="dropdown-caret"></i>
                                            </span>
                                            <p class="mnp-name">{{ $nombreUser }}</p>
                                            <span class="mnp-desc">{{ $email }}</span>
                                        </a>
                                    </div>
                                    <div id="profile-nav" class="collapse list-group bg-trans">
                                        <a href='vistaInicio' class="list-group-item">
                                            <i class="demo-pli-home icon-lg icon-fw"></i> Inicio
                                        </a>
                                        <a href="perfilD" class="list-group-item">
                                            <i class="demo-pli-male icon-lg icon-fw"></i> Ver Perfil
                                        </a>

                                        <a href="logout" class="list-group-item">
                                            <i class="demo-pli-unlock icon-lg icon-fw"></i> Salir
                                        </a>
                                    </div>
                                </div>

                                <ul id="mainnav-menu" class="list-group">
                                    <?php 
                                    
                                
                                  
                                    $Permissions =  DB::select('CALL permmisosPorRol(?)', array(Auth::user()->tipoUsuario));
        $permis = [];
        $i = 0;
        $nroPermisoPorGrupo=0;
        foreach ($Permissions as $Permissio) {
            $permis[$i++] = $Permissio->Permiso;
        }
                                    foreach ($sidebar as $categoria): 
                                    $Permisos =  DB::select('CALL permmisosPorGrupo(?)', array($categoria->id));
                                    $nroPermisoPorGrupo=0;
                                    foreach ($Permisos as $item) {
                                        if (in_Array($item->name, $permis)){
                                            $nroPermisoPorGrupo++;
                                        }
                                        
                                    }
                                    
                                    if ($nroPermisoPorGrupo>0) {
                                       

                                       
                                    ?>
                                    <li class="<?= $categoria->nombre == $categoriaActual ? 'active-sub' : '' ?>">
                                        <a href="#">
                                            <i class=" {{ $categoria->icono }}"></i>
                                            <span class="menu-title"><?php echo strtoupper($categoria->nombre); ?></span>
                                            <i class="arrow"></i>
                                        </a>
                                        <ul class="<?= $categoria->nombre == $categoriaActual ? 'collapse in' : '' ?>">
                                            <?php foreach ($Permisos as $item): 
                                            
                                            $subGrupo = $item;
                                            if (in_Array($item->name, $permis)) {?>
                                            <li class="<?= $item->ruta == $OpcionActual ? 'active-link' : '' ?>">
                                                <a href="<?= $item->ruta ?>"><?= $item->name ?></a>
                                            </li>

                                            <?php } endforeach; ?>
                                        </ul>
                                    </li>
                                    <?php  } endforeach; ?>
                                </ul>


                                <!--Widget-->
                                <!--================================-->
                                <div class="mainnav-widget">

                                    <!-- Show the button on collapsed navigation -->
                                    <div class="show-small">
                                        <a href="#" data-toggle="menu-widget" data-target="#demo-wg-server">
                                            <i class="demo-pli-monitor-2"></i>
                                        </a>
                                    </div>

                                    <!-- Hide the content on collapsed navigation -->

                                    </ul>
                                </div>
                                <!--================================-->
                                <!--End widget-->

                            </div>
                        </div>
                    </div>
                    <!--================================-->
                    <!--End menu-->

                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->

        </div>

        

        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>



            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
          



            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <p class="pad-lft">&#0169; 2024 Garzasoft</p>



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->


    
    
    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src="js\jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="js\bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="js\nifty.min.js"></script>




    <!--=================================================-->
    
    <!--Demo script [ DEMONSTRATION ]-->
    <script src="js\demo\nifty-demo.min.js"></script>

    
    <!--Flot Chart [ OPTIONAL ]-->
    <script src="plugins\flot-charts\jquery.flot.min.js"></script>
	<script src="plugins\flot-charts\jquery.flot.resize.min.js"></script>
	<script src="plugins\flot-charts\jquery.flot.tooltip.min.js"></script>


    <!--Sparkline [ OPTIONAL ]-->
    <script src="plugins\sparkline\jquery.sparkline.min.js"></script>


    <!--Specify page [ SAMPLE ]-->
    <script src="js\demo\dashboard.js"></script>


    

</body>
</html>
