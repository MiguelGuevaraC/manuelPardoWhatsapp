<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/login.css" rel="stylesheet">
    <link type="image/png" href="plantillaNuevo\img\logo.png" rel="icon">
    <title>Mensajería | Login</title>
    <title>Login</title>
    <!-- CDN para BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>
    
</head>

<body>

    <div class="parallax">
        <div class="login-wrap">
            <h3 for="" class="labelFormato" style="font-size:50px">MENSAJERÍA POR WHATSAPP</h3>
            <div class="login-html">

                <div class="login-form">
                    <br>
                    <div class="group">
                        <label for="" class="labelFormato">INICIAR SESION</label>
                    </div>
                    <br>
                    <form method="POST" action="login">
                        @csrf
                        <div class="group">
                            <input id="username" class="input block mt-1 w-full" type="text" name="username"
                                placeholder="Usuario" required autofocus />
                        </div>

                        <div class="group">
                            <input id="password" class="input block mt-1 w-full" type="password" name="password"
                                placeholder="Contraseña" required autocomplete="current-password" />
                        </div>
                        <br>
                        <div class="group">
                            <input type="submit" class="button" value="ENVIAR">
                            <br>
                            {{-- <a class="frase-olvido-contrasena" href="/password/reset">
                                ¿Olvidaste tu contraseña?
                            </a> --}}
                        </div>
                        <br>
                        <div class="hr"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--jQuery [ REQUIRED ]-->
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(".input").on('input', function() {
            var valorInput = $(this).val();

            if (valorInput !== '') {
                $(this).css('background-color', '#fff');
            } else {
                $(this).css('background-color', 'rgb(255 255 255 / 50%)');
            }

        });
    </script>

</body>

</html>
