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

        table{
            width: 100%;
            border-collapse: collapse;
            border: 1px solid;
        }
        table, th, td {
        border: 1px solid;
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

        @foreach($data-?>objectifs as $objectif)
            <h4 style="margin-top:20px;">Objectif : {{ $objectif->libobjectif }}</h4>

            @foreach($objectif?->results as $result)
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
                        @foreach($result?->suivi_results as $sr)
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