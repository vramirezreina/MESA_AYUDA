<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado del Ticket Actualizado</title>
</head>
<body>
    <h2>Hola {{ $usuario->name }}</h2>
    <p>El estado del ticket #{{ $ticket->id }} ha sido actualizado.</p>


    <ul>
        <li><strong>Tipo:</strong> {{ $ticket->tipo }}</li>
        <li><strong>Descripción:</strong> {{ $ticket->descripcion }}</li>
        <li><strong>Nuevo estado:</strong> {{ $ticket->estado }}</li>
    </ul>

    <p>Ingrese al sistema si desea revisar más detalles.</p>
</body>
</html>
