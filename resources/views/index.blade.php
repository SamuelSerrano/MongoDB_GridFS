<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Demo</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="js/app.js" defer></script>
</head>

<body>
    <br>
    <h2 class="font-weight-bold">Prueba funcionalidades</h2>
    <p class="text-primary">
        Carga Excel / Paso a JSON / Conversión a XML / Guardado de información MongoDB y MariaDB / Autenticación JWT
    </p>
    <form method="post" action="{{ route('users.import.excel') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <br><br>
        <button type="submit" class="btn btn-primary">Enviar </button>
    </form>
    <br>
    <p class="description text-danger">
        @if (Session::has('message'))
            @foreach (session()->get('message') as $in)
                <tr>
                    <td>{{ $in }}</td>
                </tr>
            @endforeach
        @endif

        @if (Session::has('message2'))
            {{ Session::get('message2') }}
        @endif

        @if (Session::has('message3'))
            <p class="text-success"> {{  Session::get('message3') }} </p>
        @endif
    </p>
    <?php echo date('H:i:sP') ?>
    <!--a href="{{ route('users.export') }}"> Descargar datos en Excel </a-->
</body>
