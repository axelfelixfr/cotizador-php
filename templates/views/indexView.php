
<?php require_once INCLUDES.'head.php'; ?>
<?php require_once INCLUDES.'navbar.php'; ?>

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
        <div class="card">
          <div class="card-header">Resumen de cotización</div>
          <div class="card-body wrapper_quote">
            <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered">
                <thead>
                  <th>Concepto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th class="text-right">Subtotal</th>
                </thead>
                
                <tbody>
                  <tr>
                    <td>Playera</td>
                    <td>1</td>
                    <td>$399.00</td>
                    <td class="text-right">$399.00</td>
                  </tr>
                  <tr>
                    <td>Ukelele</td>
                    <td>2</td>
                    <td>$250.00</td>
                    <td class="text-right">$500.00</td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="3">Subtotal</td>
                    <td class="text-right">$123.00</td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="3">Impuestos</td>
                    <td class="text-right">$123.00</td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="3">Envío</td>
                    <td class="text-right">$123.00</td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="4"><b>Total</b><h3 class="text-success">$799.00</h3></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary">Descargar PDF</button>
            <button class="btn btn-success">Enviar por correo</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin del Container -->

<?php require_once INCLUDES.'footer.php'; ?>