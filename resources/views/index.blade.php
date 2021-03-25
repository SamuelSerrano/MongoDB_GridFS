<form method="post" action="{{ route('users.import.excel') }}" enctype="multipart/form-data">
    @csrf
    @if(Session::has('message'))
        <p> {{ Session::get('message') }} </p>
    @endif
    <input type="file" name="file">
    <br><br>
    <button type="submit">Enviar </button>
</form>
<br><br>
<a href="{{ route('users.export') }}"> Descargar datos en Excel </a>