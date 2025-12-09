<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche réforme</title>
    <style>
        .container{
            margin-left:30px;
            margin-right:30px;
        }
        .inline-block-element {
            display: inline-block;
            width: 30%; /* ✅  yes, it will work */
            height: 100px; /* ✅  yes, it will work */
            text-align:center      /* distributes space on the line equally among items */
        }
        .inline-block-element2 {
            margin-left:30px;
            margin-right:30px;
            display: inline-block;
            width: 45%; /* ✅  yes, it will work */
            height: 100px; /* ✅  yes, it will work */
            text-align:center      /* distributes space on the line equally among items */
        }
        .container{
            padding-left:15px;
            padding-right:15px;
        }

       h2, h3, h4 {
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 25px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table th, table td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 12px;
        }
        table th {
            background: #f2f2f2;
        }
        header, .row, section {
            display: flex;
            margin-bottom:100px  /* aligns all child elements (flex items) in a row */
            }

            .col {
            flex: 1;  
            text-align:center      /* distributes space on the line equally among items */
            }
    </style>
</head>
<body>
<header>
    <div style="text-align:center">
        <img height="50px" src="data:image/jpeg;base64,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" alt="">
    </div>
  </header>

   <h2 style="text-align:center; text-transform:uppercase;">
        Fiche de Réforme
    </h2>

    <h3 style="text-align:center;">{{ $data->libref }}</h3>

    <!-- SECTION Informations générales -->
    <div class="section">
        <h4>Informations générales</h4>
        <table>
            <tr>
                <th>Type</th>
                <td>{{ $data->typeref }}</td>
            </tr>
            <tr>
                <th>Objectif Global</th>
                <td>{{ $data->objectif_glob }}</td>
            </tr>
            <tr>
                <th>Population Cible</th>
                <td>{{ $data->popul_cible }}</td>
            </tr>
            <tr>
                <th>Structures impliquées</th>
                <td>{{ $data->struct_impl }}</td>
            </tr>
            <tr>
                <th>Période d'exécution</th>
                <td>{{ $data->periodexe }}</td>
            </tr>
        </table>
    </div>

    <!-- SECTION Objectifs / Résultats -->
    <div class="section">
        <h4>Objectifs et Résultats</h4>

        @foreach($data?->objectifs ?? [] as $objectif)
            <h4 style="margin-top:20px;">Objectif : {{ $objectif->libobjectif }}</h4>

            @foreach($objectif?->results ?? [] as $result)
                <table>
                    <tr>
                        <th colspan="2">Résultat : {{ $result->libresult }}</th>
                    </tr>
                    <tr>
                        <th>Indicateur</th>
                        <td>{{ $result->indicateur }}</td>
                    </tr>
                    <tr>
                        <th>Valeur cible</th>
                        <td>{{ $result->valeur_cible }}</td>
                    </tr>
                    <tr>
                        <th>Valeur de référence</th>
                        <td>{{ $result->valeurref }}</td>
                    </tr>
                </table>

                <!-- suivi des résultats -->
                <h4 style="margin:10px 0 5px;">Suivi des réalisations</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Valeur réalisée</th>
                            <th>Taux de réalisation (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result?->suivi_results ?? [] as $sr)
                            <tr>
                                <td>{{ $sr->date }}</td>
                                <td>{{ $sr->valeur_realise }}</td>
                                <td>{{ $sr->taux_realisat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endforeach

        @endforeach

    </div>

    <!-- Pied de page -->
    <p style="text-align:center; margin-top:50px; font-size:11px;">
        Document généré automatiquement le {{ date('d/m/Y H:i') }}.
    </p>
</body>
</html>