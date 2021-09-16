<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cotizador App</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
  <!-- Header -->
  <header>
    <div class="bg-dark collapse" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-7 py-4">
            <h4 class="text-white">About</h4>
            <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
          </div>
          <div class="col-sm-4 offset-md-1 py-4">
            <h4 class="text-white">Contact</h4>
            <ul class="list-unstyled">
              <li><a href="#" class="text-white">Follow on Twitter</a></li>
              <li><a href="#" class="text-white">Like on Facebook</a></li>
              <li><a href="#" class="text-white">Email me</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand d-flex align-items-center">
          <strong>Cotizador App</strong>
        </a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>
<!-- Fin del Header -->

<!-- Container -->
<div class="container-fluid py-5">
  <div class="row">
    <div class="col-lg-8 col-12">
      <div class="card mb-3">
        <div class="card-header">Información del cliente</div>
        <div class="card-body">
          <form action="">
            <div class="form-group row">
              <div class="col-4">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Tu Nombre" required>
              </div>
              <div class="col-4">
                <label for="empresa">Empresa</label>
                <input type="text" class="form-control" name="empresa" id="empresa" placeholder="Tu Empresa" required>
              </div>
              <div class="col-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="nombre@email.com" required>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">Agregar nuevo concepto</div>
        <div class="card-body">
          <form action="">
            <div class="form-group row">
              <div class="col-3">
                <label for="concepto">Concepto</label>
                <input type="text" class="form-control" name="concepto" id="concepto" placeholder="Curso PHP" required>
              </div>
              <div class="col-3">
                <label for="tipo">Tipo de producto</label>
                <select name="tipo" id="tipo" class="form-control">
                  <option value="producto">Producto</option>
                  <option value="servicio">Servicio</option>
                </select>
              </div>
              <div class="col-3">
                <label for="cantidad">Cantidad</label>
                <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" max="99999" value="1" required>
              </div>
              <div class="col-3">
                <label for="precio_unitario">Precio unitario</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="text" class="form-control" name="precio_unitario" id="precio_unitario" placeholder="0.00" required>
                </div>
              </div>
            </div>

            <br>
            <button class="btn btn-success" type="submit">Agregar concepto</button>
            <button class="btn btn-danger" type="reset">Cancelar</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-12">

    </div>
  </div>
</div>
</div>
<!-- Fin del Container -->


<!-- Footer -->
<footer class="text-muted bg-light py-3">
  <div class="container">
    <p class="float-right">
      <a href="#">De vuelta arriba</a>
    </p>
    <p>Cotizador App &copy; Todos los derechos reservados <?php echo date('Y'); ?>.</p>
  </div>
</footer>
<!-- Fin del Footer -->

  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>