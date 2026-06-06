<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            color: #222;
            padding: 20px;
        }

        .titulo{
            text-align: center;
            margin-bottom: 10px;
        }

        .titulo h1{
            color: #ff6600;
            margin: 0;
            font-size: 26px;
        }

        .fecha{
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        thead{
            background: #ff6600;
            color: white;
        }

        th{
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 14px;
        }

        td{
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 13px;
        }

        tbody tr:nth-child(even){
            background: #f2f2f2;
        }

        .footer{
            margin-top: 25px;
            font-size: 11px;
            color: #777;
            text-align: center;
        }

    </style>
</head>

<body>

    <div class="titulo">
        <h1>REPORTE DE USUARIOS</h1>
    </div>

    <div class="fecha">
        Fecha de generación:
        {{ $fecha }}
    </div>

    <table>

        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
            </tr>
        </thead>

        <tbody>

            @foreach($usuarios as $index => $usuario)

            <tr>

                <td>
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $usuario->nom_usu }}
                    {{ $usuario->app_usu }}
                    {{ $usuario->apm_usu }}
                </td>

                <td>
                    {{ $usuario->email_usu }}
                </td>

                <td>
                    {{ $usuario->rol->nom_rol ?? 'Sin rol' }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <div class="footer">
        Sistema de Gestión Automotriz
    </div>

</body>
</html>