@php
    function namesPerson($person)
    {
        $nombre = '';
        if ($person->typeofDocument == 'DNI') {
            $nombre = $person->names . ' ' . $person->fatherSurname . ' ' . $person->motherSurname;
        } elseif ($person->typeofDocument == 'RUC') {
            $nombre = $person->businessName;
        }
        return $nombre;
    }

@endphp

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php
    $vista = 'Home';
    $categoriaActual = 'home';
    $OpcionActual = 'vistaInicio';
    
    ?>

    <title>Mensajería | {{ $vista }}</title>
    <link type="image/png" href="plantillaNuevo\img\logo.png" rel="icon">


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="plantillaNuevo\css\bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="plantillaNuevo\css\nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="plantillaNuevo\css\demo\nifty-demo-icons.min.css" rel="stylesheet">


    <!--=================================================-->
    <!--Custom scheme [ OPTIONAL ]-->









    <!--=================================================-->




    <link rel="stylesheet" href="/manuelPardoWhatsapp/Cdn-Locales/pkgAwsome/css/all.css" />

    <!--Demo [ DEMONSTRATION ]-->
    <link href="plantillaNuevo\css\demo\nifty-demo.min.css" rel="stylesheet">


    <link href="plantillaNuevo\css\themes\type-c\theme-navy.min.css" rel="stylesheet">

    <!--Unite Gallery [ OPTIONAL ]-->
    <link href="plantillaNuevo\plugins\unitegallery\css\unitegallery.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/appPlantilla.css') }}">
    <!--=================================================

    REQUIRED
    You must include this in your project.


    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.


    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.


    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.


    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    =================================================-->

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
                        {{-- <img src="plantillaNuevo\img\logo.png" alt="Nifty Logo" class="brand-icon"> --}}
                        <div class="brand-title">
                            <span class="brand-text">Mensajaría</span>
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
                        <?php foreach ($groupMenu as $item): ?>

                        @foreach ($item['option_menus'] as $menu)
                            <li id="dropdown-<?php echo $item['id']; ?>" class="dropdown">
                                <a href="{{ $menu['route'] }}" data-tooltip="{{ $menu['name'] }}"
                                    class="btn-profesional dropdown-toggle text-right">
                                    <span class="ic-user pull-right">
                                        <i style="font-size:21px" class="{{ $menu['icon'] }}"></i>
                                    </span>
                                </a>
                            </li>
                        @endforeach

                        <?php endforeach; ?>

                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-tooltip="Perfil" data-toggle="dropdown"
                                class="btn-profesional dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i style="font-size:21px" class="fa-solid fa-user"></i>
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="perfilD"><i class="demo-pli-male icon-lg icon-fw"></i> Perfil</a>
                                    </li>
                                    <li>
                                        <a href="logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Salir</a>
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

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div class="pad-all text-center">
                        <h3>Bienvenido a Mensajería por WhatsApp</h3>
                        <hr class="my-4">
                        <p>Descubre nuestra exclusiva plataforma para enviar mensajes por whatsaApp. Puedes cargar los
                            datos de los estudiantes y enviar notificaciones a los padres sobre los pagos pendientes.
                        </p>

                        <p>De la mano de <b>MrSoft</b>, solucionando juntos. Agradecemos su elección y confianza. !</p>
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                        <li><a href='vistaInicio'><i class="demo-pli-home"></i></a></li>
                        <li><a href="{{ $OpcionActual }}">{{ $categoriaActual }}</a></li>

                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>


                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">

                    <div class="panel">


                        <div class="pad-all">
                            <div id="demo-gallery" style="display:none;">

                                <a href="#">
                                    <img alt="The winding road" src="plantillaNuevo\img\gallery\thumbs\tile1.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile1.jpeg"
                                        data-description="The winding road description" style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Pancake" src="plantillaNuevo\img\gallery\thumbs\tile2.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile2.jpeg"
                                        data-description="A pancake is a flat cake, often thin and round, prepared from a starch-based batter"
                                        style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Foreshore" src="plantillaNuevo\img\gallery\thumbs\tile3.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile3.jpeg"
                                        data-description="The part of a shore between high- and low-water marks, or between the water and cultivated or developed land."
                                        style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Yellow Flowers" src="plantillaNuevo\img\gallery\thumbs\tile4.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile4.jpeg"
                                        data-description="Those are yellow flowers" style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Waterfall" src="plantillaNuevo\img\gallery\thumbs\tile5.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile5.jpeg"
                                        data-description="A waterfall is a place where water flows over a vertical drop or a series of steep drops in the course of a stream or river."
                                        style="display:none">
                                </a>



                                <a href="#">
                                    <img alt="In the jungle" src="plantillaNuevo\img\gallery\thumbs\tile7.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile7.jpeg"
                                        data-description="This is my car" style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Note" src="plantillaNuevo\img\gallery\thumbs\tile8.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile8.jpeg"
                                        data-description="This is a note" style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Off-Road Motorcycle" src="plantillaNuevo\img\gallery\thumbs\tile9.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile9.jpeg"
                                        data-description="This is a motorcycle" style="display:none">
                                </a>



                                <a href="#">
                                    <img alt="The winding road" src="plantillaNuevo\img\gallery\thumbs\tile6.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile6.jpeg"
                                        data-description="The winding road description" style="display:none">
                                </a>

                                <a href="#">
                                    <img alt="Pancake" src="plantillaNuevo\img\gallery\thumbs\tile2.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile2.jpeg"
                                        data-description="A pancake is a flat cake, often thin and round, prepared from a starch-based batter"
                                        style="display:none">
                                </a>


                                <a href="#">
                                    <img alt="Yellow Flowers" src="plantillaNuevo\img\gallery\thumbs\tile4.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile4.jpeg"
                                        data-description="Those are yellow flowers" style="display:none">
                                </a>




                                <a href="#">
                                    <img alt="Note" src="plantillaNuevo\img\gallery\thumbs\tile10.jpeg"
                                        data-image="plantillaNuevo\img/gallery/thumbs/tile10.jpeg"
                                        data-description="This is a note" style="display:none">
                                </a>


                            </div>
                        </div>
                    </div>



                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->






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
                                            <p class="mnp-name">{{ $user->typeUser->name }}</p>
                                            <span class="mnp-desc">{{ namesPerson($user->person) }}</span>
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
                                    <?php foreach ($groupMenuLeft as $categoria): ?>
                                    <?php if (!empty($categoria['option_menus']) && count($categoria['option_menus']) > 0): ?>
                                    <li class="<?= $categoria['nombre'] == $categoriaActual ? 'active-sub' : '' ?>">
                                        <a href="#">
                                            <i class="<?= $categoria['icon'] ?>"></i>
                                            <span class="menu-title"><?= strtoupper($categoria['name']) ?></span>
                                            <i class="arrow"></i>
                                        </a>
                                        <ul class="<?= $categoria['name'] == $categoriaActual ? 'collapse in' : '' ?>">
                                            <?php foreach ($categoria['option_menus'] as $item): ?>
                                            <li class="<?= $item['route'] == $OpcionActual ? 'active-link' : '' ?>">
                                                <a class="optionsMenu" href="<?= $item['route'] ?>">
                                                    <i class="<?= $item['icon'] ?>"></i> <?= $item['name'] ?>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>






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
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending
                    action.</a>
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
    <script src="plantillaNuevo\js\jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="plantillaNuevo\js\bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="plantillaNuevo\js\nifty.min.js"></script>


    <!-- PARA MODAL DE ALERTAS SWEETALERT2-->
    <script src="/manuelPardoWhatsapp/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>

    <!--=================================================-->

    <!--Demo script [ DEMONSTRATION ]-->
    <script src="plantillaNuevo\js\demo\nifty-demo.min.js"></script>


    <!--Unite Gallery [ OPTIONAL ]-->
    <script src="plantillaNuevo\plugins\unitegallery\js\unitegallery.min.js"></script>
    <script src="plantillaNuevo\plugins\unitegallery\themes\tiles\ug-theme-tiles.js"></script>



    <!--Custom script [ DEMONSTRATION ]-->
    <!--===================================================-->

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/JqueryInicio/JqueryInicio.js') }}"></script>
    <script src="/manuelPardoWhatsapp/Cdn-Locales/pkgAwsome/js/all.js"></script>


    <script>
        $(document).on('nifty.ready', function() {


            $("#demo-gallery").unitegallery({
                tiles_type: "nested"
            });


            $("#demo-gallery-2").unitegallery({
                tiles_type: "nested",
                tiles_nested_optimal_tile_width: 150
            });


        });
    </script>
</body>

</html>
