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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php
    $vista = 'Messages';
    $categoriaActual = 'Movimientos';
    $OpcionActual = 'message';
    
    ?>

    <title>Mensajería | {{ $vista }}</title>
    <link type="image/png" href="plantillaNuevo\img\logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">



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
    <!--Custom scheme [ OPTIONAL ]-->
    <link href="plantillaNuevo\css\themes\type-c\theme-navy.min.css" rel="stylesheet">


    <!--=================================================-->



    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="plantillaNuevo\plugins\pace\pace.min.css" rel="stylesheet">
    <script src="plantillaNuevo\plugins\pace\pace.min.js"></script>


    <!--Demo [ DEMONSTRATION ]-->
    <link href="plantillaNuevo\css\demo\nifty-demo.min.css" rel="stylesheet">

    <!--Bootstrap Table [ OPTIONAL ]-->
    <link href="plantillaNuevo\plugins\bootstrap-table\bootstrap-table.min.css" rel="stylesheet">



    <!--Animate.css [ OPTIONAL ]-->
    <link href="plantillaNuevo\plugins\animate-css\animate.min.css" rel="stylesheet">




    <!-- CSS DEL DATATABLE -->
    <link rel="stylesheet" href="/manuelPardoWhatsapp/Cdn-Locales/pkgDatatables/datatables.css">
    <link rel="stylesheet" href="/manuelPardoWhatsapp/Cdn-Locales/pkgAwsome/css/all.css" />

    <link rel="stylesheet" href="{{ asset('css/appPlantilla.css') }}">

    <style>
        .panel-container {
            display: flex;
            justify-content: space-between;

        }

        .panel-half {
            width: 100%;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #000000;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .panel-half:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .media-left .pad-hor {
            padding: 10px;
            background-color: #25476a;
            border-radius: 50%;
        }

        .text-2x {
            font-size: 2em;
        }

        .text-semibold {
            font-weight: 600;
        }

        .mar-no {
            margin: 0;
        }

        .pad-all {
            padding: 20px;
        }

        .panel-success {
            border: 1px solid #28a745;
            background-color: #28a745;
            color: #fff;
        }
    </style>

    <style>
        .panel {
            border: 1px solid #ccc;
            padding: 20px;
            color: white;
            margin: 30px;
            background-color: #003042;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .panel-container {
            margin-bottom: 20px;
        }

        .panel-success {
            background-color: #dff0d8;
            padding: 10px;
            border-radius: 8px;
        }

        .panel-body {
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .textarea-container {
            margin: 20px 0;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            color: black;
            border-radius: 4px;
            resize: vertical;
            font-family: monospace;
            box-sizing: border-box;
        }

        textarea.large {
            height: 70px;
        }

        textarea.largTitle {
            height: 40px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
    </style>


</head>

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
                            <span class="brand-text">Mensajería</span>
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
                    <div id="page-title">

                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                        <li><a href='vistaInicio'><i class="demo-pli-home"></i></a></li>
                        <li><a href="{{ $OpcionActual }}">{{ $categoriaActual }}</a></li>
                        <li><a href="{{ $OpcionActual }}">{{ $vista }}</a></li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>


                <!--Page content-->
                <!--===================================================-->
                <div class="panel">
                    <div id="demo-custom-toolbar2" class="" style="text-align: right">
                        <button id="btonShowEtiquetas" style="margin: 12px" class="btn btn-danger"><i class="demo-pli-plus"></i> Ver Etiquetas </button>
                        <button id="btonShowView" style="margin: 12px" class="btn btn-warning"><i class="demo-pli-plus"></i> Vista Mensaje </button>
                    </div>
                    <div id="messageForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="textarea-container">
                            <label for="title">TÍTULO</label>
                            <textarea id="title" class="largTitle">{{$message->title ?? ''}}</textarea>
                        </div>
                        <div class="textarea-container">
                            <label for="block1">PARRAFO 1</label>
                            <textarea id="block1" class="large">{{$message->block1 ?? ''}}</textarea>
                        </div>
                        <div class="textarea-container">
                            <label for="block2">PARRAFO 2</label>
                            <textarea id="block2" class="large">{{$message->block2 ?? ''}}</textarea>
                        </div>
                        <div class="textarea-container">
                            <label for="block3">PARRAFO 3</label>
                            <textarea id="block3" class="large">{{$message->block3 ?? ''}}</textarea>
                        </div>
                    </div>
                    <div id="demo-custom-toolbar2" class="" style="text-align: center">
                        <button id="btonSaveMessage" style="margin: 12px" class="btn btn-success"><i class="demo-pli-plus"></i> Guardar </button>
                    </div>
                </div>
                
                <!--===================================================-->
                <!--END CONTENT CONTAINER-->



                <!--ASIDE-->
                <!--===================================================-->

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
                                                    src="plantillaNuevo\img\profile-photos\1.png"
                                                    alt="Profile Picture">
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
                                        <li
                                            class="<?= $categoria['nombre'] == $categoriaActual ? 'active-sub' : '' ?>">
                                            <a href="#">
                                                <i class="<?= $categoria['icon'] ?>"></i>
                                                <span class="menu-title"><?= strtoupper($categoria['name']) ?></span>
                                                <i class="arrow"></i>
                                            </a>
                                            <ul
                                                class="<?= $categoria['name'] == $categoriaActual ? 'collapse in' : '' ?>">
                                                <?php foreach ($categoria['option_menus'] as $item): ?>
                                                <li
                                                    class="<?= $item['route'] == $OpcionActual ? 'active-link' : '' ?>">
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



                                    <!--Widget-->
                                    <!--================================-->
                                    <div class="mainnav-widget">

                                        <!-- Show the button on collapsed navigation -->
                                        <div class="show-small">
                                            <a href="#" data-toggle="menu-widget"
                                                data-target="#demo-wg-server">
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

            <!--===================================================-->
        </div>


        <!-- MODALES -->


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




        <!--=================================================-->

        <!--Demo script [ DEMONSTRATION ]-->
        <script src="plantillaNuevo\js\demo\nifty-demo.min.js"></script>


        <!-- JS DE DATATABLE -->
        <script src="/manuelPardoWhatsapp/Cdn-Locales/pkgDatatables/datatables.js"></script>

        <script src="/manuelPardoWhatsapp/Cdn-Locales/pkgAwsome/js/all.js"></script>

        <!--Bootstrap Table Sample [ SAMPLE ]-->
        <script src="plantillaNuevo\js\demo\tables-bs-table.js"></script>


        <!--X-editable [ OPTIONAL ]-->
        <script src="plantillaNuevo\plugins\x-editable\js\bootstrap-editable.min.js"></script>


        <!--Bootstrap Table [ OPTIONAL ]-->
        <script src="plantillaNuevo\plugins\bootstrap-table\bootstrap-table.min.js"></script>


        <!--Bootstrap Table Extension [ OPTIONAL ]-->
        <script src="plantillaNuevo\plugins\bootstrap-table\extensions\editable\bootstrap-table-editable.js"></script>


        <!--Bootbox Modals [ OPTIONAL ]-->
        <script src="plantillaNuevo\plugins\bootbox\bootbox.min.js"></script>


        <!-- SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

        <!--Modals [ SAMPLE ]-->
        {{-- <script src="{{ asset('js/JqueryMessage/JqueryIndexMessage.js') }}"></script> --}}


        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <script src="{{ asset('js/JqueryMessage/JqueryMessage.js') }}"></script>


</body>

</html>
