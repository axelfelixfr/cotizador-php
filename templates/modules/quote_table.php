<?php if(empty($d->items)): ?>
<div class="text-center">
  <h3>La cotización esta vacía</h3>
  <img src="<?php echo IMG.'empty.png'; ?>" alt="Sin contenido" class="img-fluid" style="width: 150px;">
</div>
<?php else: ?>

<div class="card-body wrapper_quote p-0">
  <div class="table-responsive">
    <table class="table table-hover table-striped table-bordered">
      <thead>
        <th>Concepto</th>
        <th>Precio</th>
        <th class="text-center">Cantidad</th>
        <th class="text-right">Subtotal</th>
      </thead>
      
      <tbody>
        <?php foreach($d->items as $item): ?>
        <tr>
          <td><?php echo $item->concept; ?></td>
          <td><?php echo '$'.number_format($item->price, 2); ?></td>
          <td class="text-center"><?php echo $item->quantity; ?></td>
          <td class="text-right"><?php echo '$'.number_format($item->total, 2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td class="text-right" colspan="3">Subtotal</td>
          <td class="text-right"><?php echo '$'.number_format($d->subtotal, 2); ?></td>
        </tr>
        <tr>
          <td class="text-right" colspan="3">Impuestos</td>
          <td class="text-right"><?php echo '$'.number_format($d->taxes, 2); ?></td>
        </tr>
        <tr>
          <td class="text-right" colspan="3">Envío</td>
          <td class="text-right"><?php echo '$'.number_format($d->shipping, 2); ?></td>
        </tr>
        <tr>
          <td class="text-right" colspan="4">
            <b>Total</b><h3 class="text-success"><b><?php echo '$'.number_format($d->total, 2); ?></b></h3>
            <small class="text-muted"><?php echo sprintf('Impuestos incluidos %s%% IVA', TAXES_RATE); ?></small>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php endif; ?>
