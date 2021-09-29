<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización</title>
  <style type="text/css">
  * {
   font-family: Verdana, Arial, Helvetica, sans-serif;
  }

  table {
    font-size: x-small;
  }

  tfoot tr td {
    font-weight: bold;
    font-size: x-small;
  }

  .gray {
    background-color: lightgray;
  }

  .success {
    color: green;
  }
  </style>
</head>
<body>
  <!-- Cabecera -->
  <table width="100%">
    <tr>
      <td valign="top"><img src="<?php echo 'assets/img/logo.png'; ?>" alt="" width="150"/></td>
      <td align="right">
        <h3><?php echo APP_NAME; ?></h3>
        <pre>
          Axel Félix CEO
          XX10101011101
          5516 5356 42
          FAX
        </pre>
      </td>
    </tr>
  </table>

  <!-- Información de la empresa -->
  <table width="100%">
    <tr>
      <td><strong>De:</strong> Axel Félix</td>
      <td><strong>Para:</strong> Cliente - Empresa (email@empresa.com)</td>
    </tr>
  </table>

  <br/>

  <!-- Resumen de la cotización -->
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Descripción</th>
        <th>Precio unitario</th>
        <th>Cantidad</th>
        <th>Total</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>Playstation 4 Black</td>
        <td align="right">$1400.00</td>
        <td align="center">1</td>
        <td align="right">$1400.00</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Metal Gear Solid</td>
        <td align="right">$105.00</td>
        <td align="center">1</td>
        <td align="right">$105.00</td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td>Final Fantasy</td>
        <td align="right">$140.00</td>
        <td align="center">1</td>
        <td align="right">$140.00</td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td colspan="3"></td>
        <td align="right">Subtotal $</td>
        <td align="right">1535.00</td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td align="right">Impuestos $</td>
        <td align="right">294.3</td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td align="right">Envío $</td>
        <td align="right">294.30</td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td align="right">Total $</td>
        <td align="right" class="gray"><h3 style="margin: 0px 0px;">1929.3</h3></td>
      </tr>
      <tr>
        <td colspan="5" align="right">Todos los impuestos incluidos</td>
      </tr>
    </tfoot>
  </table>
</body>
</html>