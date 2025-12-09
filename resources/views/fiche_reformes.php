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

  <h1 style="text-align:center">Fiche détails réformes</h1>
</body>
</html>