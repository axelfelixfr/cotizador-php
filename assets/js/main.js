$('document').ready(() => {
  // Función para las notificaciones
  function notify(content, type = 'success') {
    // Seleccionamos el wrapper que esta en el HTML (<div class="wrapper_notifications"></div>)
    let wrapper = $('.wrapper_notifications'),
      id = Math.floor(Math.random() * 500 + 1),
      // Creamos la alerta y le pasamos el type y su contenido (content)
      notification = `<div class="alert alert-${type}" id="notify_${id}">${content}</div>`,
      time = 5000;

    // Insertar en el contenedor la notificación
    // Se usa append() en vez de html() ya que no se quiere reemplazar el HTML que ya esta, solo se quiere agregar más
    wrapper.append(notification);

    // Timer para ocultar las notificaciones
    setTimeout(() => {
      // wrapper.html('');
      $('#notify_' + id).remove();
    }, time);

    return true;
  }

  // Cargar contenido de la cotización
  function get_quote() {
    let wrapper = $('.wrapper_quote'),
      action = 'get_quote_resumen',
      name = $('#nombre'),
      company = $('#empresa'),
      email = $('#email');

    $.ajax({
      // url, La ruta a la cual vamos a llamar o enviar información
      url: 'ajax.php',
      // type, El tipo de petición que es
      type: 'get',
      // cache, Para que no almacene información en cache, lo ponemos en false
      cache: false,
      // dataType, El tipo de información que preferimos que regrese, en este caso json
      dataType: 'json',
      // data, La información que mandamos
      data: { action },
      beforeSend: function () {
        wrapper.waitMe();
      }
      // done, se ejecuta cuando la respuesta se realizo de forma correcta
    })
      .done(res => {
        if (res.status === 200) {
          name.val(res.data.quote.name);
          company.val(res.data.quote.company);
          email.val(res.data.quote.email);
          wrapper.html(res.data.html);
        } else {
          name.val('');
          company.val('');
          email.val('');
          wrapper.html(res.msg);
        }
        console.log(res);
      })
      .fail(error => {
        wrapper.html('Ocurrió un error, recarga la página...');
        console.log(error);
      })
      .always(() => {
        wrapper.waitMe('hide');
      });
  }

  get_quote();

  // Función para agregar un concepto a la cotización
  $('#add_to_quote').on('submit', e => {
    e.preventDefault();

    let form = $('#add_to_quote'),
      action = 'add_to_quote',
      // data, Objeto que se obtuvo con new FormData donde se le pasa el formulario
      // Se pone get(0) para obtener el primer formulario que este en el DOM
      data = new FormData(form.get(0)),
      errors = 0;

    // Agregar la acción al objeto data
    data.append('action', action);

    // Validar los campos del formulario
    let concepto = $('#concepto').val(),
      precio = parseFloat($('#precio_unitario').val());

    // Lanza alerta sin el concepto es menor a 5 carácteres
    if (concepto.length < 5) {
      notify('Ingresa un concepto válido por favor', 'danger');
      errors++;
    }

    // Validar el precio
    if (precio < 10) {
      notify('Por favor ingresa un precio mayor a $10.00', 'danger');
      errors++;
    }

    // Si hay errores regresa un false para detener la ejecución
    if (errors > 0) {
      notify('Completa el formulario', 'danger');
      return false;
    }

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      cache: false,
      processData: false,
      contentType: false,
      // Le enviamos la data del formulario y el action en mismo objeto
      data: data,
      beforeSend: () => {
        // Se activa el loading en el div donde esta el formulario
        form.waitMe();
      }
    })
      .done(res => {
        // El status 201 es de éxito
        if (res.status === 201) {
          // Se muestra mensaje de éxito
          notify(res.msg);
          // Se hace reset del formulario para vaciar los campos
          form.trigger('reset');
          // Se hace la cotización
          get_quote();
        } else {
          // Se muestra mensaje de error
          notify(res.msg, 'danger');
        }
        // fail entra más en error de servidor
      })
      .fail(err => {
        notify(
          'Hubo un problema con la petición, intentalo de nuevo',
          'danger'
        );
        form.trigger('reset');
      })
      .always(() => {
        // Se quita el loading ya sea que haya caido en done o en fail la petición
        form.waitMe('hide');
      });
  });
});
