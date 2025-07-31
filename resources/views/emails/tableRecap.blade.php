<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { color: #003366; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 8px;
            font-size: 14px;
        }
        th {
            background-color: #003366;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 15px;
            padding: 8px;
            background-color: #f1f1f1;
            font-size: 15px;
        }
    </style>
</head>
<body>

    <h2>📌 Tableau de bord des réformes – Structure #{{ $structureId }}</h2>

    <p>Bonjour {{ $notifiable->firstname ?? 'Utilisateur' }},</p>

    <p>Vous recevez ce résumé automatique en tant qu’utilisateur ayant le rôle publication.</p>

    <div class="summary">
        <p><strong>{{ $reformes->count() }}</strong> réforme(s) arrivent à échéance :</p>
        @if ($urgentCount) <p>🔴 <strong>{{ $urgentCount }}</strong> réforme(s) expirent <strong>aujourd’hui</strong></p> @endif
        @if ($highPriorityCount) <p>🟠 <strong>{{ $highPriorityCount }}</strong> réforme(s) expirent dans <strong>3 jours</strong></p> @endif
        @if ($mediumPriorityCount) <p>🟡 <strong>{{ $mediumPriorityCount }}</strong> réforme(s) expirent dans <strong>10 jours</strong></p> @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Statut</th>
                <th>Échéance</th>
                <th>Priorité</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reformes as $reforme)
                <tr>
                    <td>{{ $reforme->libref }}</td>
                    <td>{{ $reforme->libelle }}</td>
                    <td>{{ $reforme->etat_mor }}</td>
                    <td>{{ \Carbon\Carbon::parse($reforme->date_fin)->format('d/m/Y') }}</td>
                    <td>
                        @switch($reforme->days_remaining)
                            @case(0) 🔴 Aujourd’hui @break
                            @case(3) 🟠 3 jours @break
                            @case(10) 🟡 10 jours @break
                            @default Autres
                        @endswitch
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px;">
        👉 <a href="{{ config('app.url') }}/reformes/dashboard">Accéder au tableau de bord</a>
    </p>

    <p style="margin-top: 10px;">Merci de votre attention.<br>L’équipe Réformes</p>

</body>
</html>
