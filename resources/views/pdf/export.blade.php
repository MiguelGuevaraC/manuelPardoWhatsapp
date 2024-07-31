<!DOCTYPE html>
<html>

<head>
    <title>Exportación PDF</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Servicio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
        * {
            margin: 10;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            text-align: center;
        }

        body {
            padding-top: 10px;
            padding-bottom: 10px;

        }

        td,
        th {
            padding: 2px;
        }

        .headerImage {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .footerImage {
            position: absolute;
            bottom: 0;
            left: 0;
            width: auto;
            height: auto;
            max-width: 100%;
        }

        .content {
            margin-top: 50px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .contentImage {
            width: 100%;
            text-align: right;
        }

        .logoImage {
            width: auto;
            height: 60px;
        }

        .titlePresupuesto {
            font-size: 32px;
            font-weight: bolder;
            text-align: center;
            margin-top: 5px;
            /*margin-bottom: 20px;*/
            color: #1caa00;
            ;
        }



        table {

            width: 95%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .tableInfo {

            margin-top: 10px;

        }

        .tablePeople {
            margin-top: 10px;
            font-size: 16px;
            border: 1px solid black;
        }

        .tablePeople td,
        .tablePeople th {
            border: 1px solid black;
        }

        .tablePeople th {
            background-color: black;
            color: white;
            text-align: left;

        }

        .tableDetail {
            margin-top: 10px;
            border-collapse: collapse;
        }

        .p10 {
            padding: 10px;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .font-12 {
            font-size: 12px;
        }

        .font-14 {
            font-size: 14px;
        }

        .font-16 {
            font-size: 16px;
        }

        .margin20 {
            margin-top: 10px;
        }

        .bolder {
            font-weight: bolder;
        }

        .tablePeople td.left {
            padding: 2px;
        }

        .tablePeople td.right {
            padding: 2px;
        }

        .tableDetail th {
            background-color: #1caa00;
            color: white;
            padding: 10px;
            font-weight: bolder;
            border: 1px solid #ddd;
        }

        .tableDetail td,
 {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;

        }

        .id {

            text-align: center;
        }

        .description {
            width: 50%;
        }


        .w50 {
            width: 50%;
        }

        .w40 {
            width: 40%;
        }

        .w20 {
            width: 20%;
        }


        .borderTop {
            border-top: 1px solid #3b3b3b;
        }

        .text-sm {
            font-size: 9px;
        }

        .w40 {
            width: 40%;
        }

        .w25 {
            width: 25%;
        }

        .w10 {
            width: 10%;
        }

        .w30 {
            width: 30%;
        }
    </style>
</head>

<body>

    <table class="tableInfo">
        <tr>
            <div class="contentImage">
                <img class="logoImage" src="{{ asset('storage/img/logo.png') }}" alt="logoTransporte">
            </div>
            <td class="">
                <div class="titlePresupuesto">REPORTE DE MENSAJERÍA ({{ $dateStart }} | {{ $dateEnd }})</div>
            </td>
        </tr>

    </table>
    <table class="tableDetail font-12">
        <thead>
            <tr>
                <th>Cuotas Vencidas</th>
                <th>Estudiante</th>
                <th>Padres</th>
                <th>Info Estudiante</th>
                <th>Teléfono</th>
                <th>Meses</th>
                <th>Monto Pago</th>
                <th>Fecha Envío</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $moviment)
                <tr>
                    <td>{{ $moviment->cuota }}</td>
                    <td>{{ $moviment->dniStudent }} | {{ $moviment->namesStudent }}</td>
                    <td>{{ $moviment->namesParent ?? '-' }}</td>
                    <td>{{ $moviment->infoStudent ?? '' }}</td>
                    <td>{{ $moviment->telephone ?? '-' }}</td>
                    <td>{{ $moviment->conceptSend ?? '-' }}</td>
                    <td>{{ $moviment->paymentAmount ?? '-' }}</td>
                    <td>{{ $moviment->created_at ? \Carbon\Carbon::parse($moviment->created_at)->format('Y-m-d H:i:s') : '-' }}</td>

                  
            @endforeach



        </tbody>
    </table>




</body>

</html>
