
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ticket</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #c6d219;
            text-align: left;
        }
        img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Usuario</th>
            <th>Estado</th>
            
        </tr>
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->tipo }}</td>
            <td>{{ $ticket->descripcion }}</td>
            <td>{{ $ticket->creador->name }}</td>
            <td>{{ $ticket->estado }}</td>
        </tr>
    </table>
</body>
</html>
