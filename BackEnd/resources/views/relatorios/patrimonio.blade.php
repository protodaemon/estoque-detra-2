<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Patrimônio</title>
    <p style="text-align: center; font-size: 12px; color: #666;">
        Gerado em: {{ now()->format('d/m/Y H:i') }}
    </p>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Relatório de Patrimônio</h1>
    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
            <tr>
                <td>{{ $produto->numero_identificacao }}</td>
                <td>{{ $produto->nome }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>