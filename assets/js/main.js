$(document).ready(() => {
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
      notify('Ingresa un concepto válido por favor', 'warning');
      errors++;
    }

    // Validar el precio
    if (precio < 10) {
      notify('Por favor ingresa un precio mayor a $10.00', 'warning');
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

  // Función para reiniciar la cotización
  $('.restart-quote').on('click', e => {
    e.preventDefault();

    // En el button guardamos el elemento al que se le da click con $(this)
    let button = $(this),
      action = 'restart_quote';

    // Se muestra un dialogo de confirmación para verificar el reinicio
    if (!confirm('¿Estás seguro?')) return false;

    // Petición
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      data: { action }
    })
      .done(res => {
        if (res.status === 200) {
          // Se muestra mensaje de éxito
          notify(res.msg);
          // Recarga la cotización
          get_quote();
        } else {
          // Se muestra mensaje de error
          notify(res.msg, 'danger');
        }
      })
      // Errores del servidor
      .fail(err => {
        notify('Hubo un problema con la petición', 'danger');
      });
  });

  // Función para borrar un concepto
  $('body').on('click', '.delete_concept', delete_concept);
  function delete_concept(e) {
    // Prevenimos el evento
    e.preventDefault();

    // En el button guardamos el elemento al que se le da click con $(this)
    let button = $(this),
      // Ingresamos al id del button al que se le da click
      id = button.data('id'),
      // Sería este: <button data-id=""></button>
      action = 'delete_concept';

    // Se muestra un dialogo de confirmación para verificar el reinicio
    if (!confirm('¿Estás seguro?')) return false;

    // Petición
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      // Se manda el id que se obtuvo por el button y la action
      // Es necesario mandar el id, para identificar que concepto borrar
      data: { action, id },
      beforeSend: () => {
        $('body').waitMe();
      }
    })
      .done(res => {
        if (res.status === 200) {
          // Se muestra mensaje de éxito
          notify(res.msg);
          // Recarga la cotización para actualizar los montos
          get_quote();
        } else {
          notify(res.msg, 'danger');
        }
      })
      .fail(err => {
        notify('Hubo un problema con la petición', 'danger');
      })
      .always(() => {
        // Sin importar que haya si caido en done o fail la petición, queremos que se oculte el loading con waitMe('hide')
        $('body').waitMe('hide');
      });
  }

  // Función para cargar la información de un concepto
  $('body').on('click', '.edit_concept', edit_concept);
  function edit_concept(e) {
    e.preventDefault();

    let button = $(this),
      id = button.data('id'),
      action = 'edit_concept',
      wrapper_update_concept = $('.wrapper_update_concept'),
      form_update_concept = $('#save_concept');

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      data: { action, id },
      beforeSend: () => {
        $('body').waitMe();
      }
    })
      .done(res => {
        if (res.status === 200) {
          // Se toma cada campo del formulario y se le pasa la data que se llego en la 'res'
          // Seleccionamos el campo 'id_concepto' que esta dentro del div con id '#save_concept'
          $('#id_concepto', form_update_concept).val(res.data.id);
          // Seleccionamos el nombre del concepto
          $('#concepto', form_update_concept).val(res.data.concept);
          // Seleccionamos la opción del tipo de concepto, pero esta vez accediendo a su atributo 'value' que esta dentro del div con id '#save_concept'
          $(
            '#tipo option[value="' + res.data.type + '"]',
            form_update_concept
          ).attr('selected', true);
          // Seleccionamos el campo de 'concepto' que esta dentro del div con id '#save_concept'
          $('#cantidad', form_update_concept).val(res.data.quantity);
          // Seleccionamos el campo de 'precio_unitario' que esta dentro del div con id '#save_concept'
          $('#precio_unitario', form_update_concept).val(res.data.price);
          // Hacemos que wrapper aparezca en el DOM con el método fadeIn()
          // El wrapper contiene el formulario
          wrapper_update_concept.fadeIn();
          // Se manda la notificación de éxito
          notify(res.msg);
        } else {
          // Se manda la notificación de fallo
          nofity(res.msg, 'danger');
        }
      })
      .fail(err => {
        notify('Hubo un problema con la petición', 'danger');
      })
      .always(() => {
        $('body').waitMe('hide');
      });
  }

  // Función para guardar cambios de concepto editado
  $('#save_concept').on('submit', e => {
    e.preventDefault();

    let form = $('#save_concept'),
      action = 'save_concept',
      // data, Objeto que se obtuvo con new FormData donde se le pasa el formulario
      // Se pone get(0) para obtener el primer formulario que este en el DOM
      data = new FormData(form.get(0)),
      wrapper_update_concept = $('.wrapper_update_concept'),
      errors = 0;

    // Agregar la acción al objeto data
    data.append('action', action);

    // Se obtienen los valores de los campos del formulario
    let concepto = $('#concepto', form).val(),
      precio = parseFloat($('#precio_unitario', form).val());

    // Lanza alerta sin el concepto es menor a 5 carácteres
    if (concepto.length < 5) {
      notify('Ingresa un concepto válido por favor', 'warning');
      errors++;
    }

    // Validar el precio
    if (precio < 10) {
      notify('Por favor ingresa un precio mayor a $10.00', 'warning');
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
      // El cache es para limpiar la cache del formulario
      cache: false,
      // processData y contentType es para que la información se codifique de forma correcta
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
        // El status 200 es de éxito
        if (res.status === 200) {
          // Se esconde nuevamente el formulario con el método fadeOut
          wrapper_update_concept.fadeOut();
          // Se hace reset del formulario para vaciar los campos
          form.trigger('reset');
          // Se muestra mensaje de éxito
          notify(res.msg);
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
        // Se esconde nuevamente el formulario con el método fadeOut
        wrapper_update_concept.fadeOut();
        // Se hace reset del formulario para vaciar los campos
        form.trigger('reset');
      })
      .always(() => {
        // Se quita el loading ya sea que haya caido en done o en fail la petición
        form.waitMe('hide');
      });
  });

  // Cancelar edición de conceptos
  $('#cancel_edit').on('click', e => {
    e.preventDefault();

    let button = $(this),
      wrapper = $('.wrapper_update_concept'),
      form = $('#save_concept');

    // Se esconde el wrapper
    wrapper.fadeOut();
    // Se resetea el formulario
    form.trigger('reset');
  });

  // $('body').on('click', '#generatequote', generate_quote);
  $('#generate_quote').on('click', e => {
    console.log('Si paso');
    e.preventDefault();
    let button = $(this),
      default_text = button.html(),
      new_text = 'Volver a generar',
      download = $('#download_quote'),
      send = $('#send_quote'),
      nombre = $('#nombre').val(),
      empresa = $('#empresa').val(),
      email = $('#email').val(),
      action = 'generate_quote',
      errors = 0;

    // Validación de la acción
    if (!confirm('¿Estás seguro?')) return false;

    // Validación de la información
    if (nombre.length < 5) {
      notify('Ingresa un nombre para el cliente por favor', 'danger');
      errors++;
    }

    if (empresa.length < 5) {
      notify('Ingresa una empresa válida por favor', 'danger');
      errors++;
    }

    if (email.length < 5) {
      notify('Ingresa una dirección de correo válida por favor', 'danger');
      errors++;
    }

    if (errors > 0) {
      return false;
    }

    // Petición ajax
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      cache: false,
      // Se manda tanto la action como los valores del formulario de información del cliente
      data: { action, nombre, empresa, email },
      beforeSend: () => {
        // Se pone el loading en el body
        $('body').waitMe();
        // El contenido del button cambia a 'Generando...'
        button.html('Generando...');
      }
    })
      .done(res => {
        if (res.status === 200) {
          notify(res.msg);
          download.fadeIn();
          send.fadeIn();
          button.html(new_text);
        } else {
          notify(res.msg, 'danger');
          download.fadeOut();
          send.fadeOut();
          button.html('Reintentar');
        }
      })
      .fail(err => {
        notify('Hubo un problema con la petición, intenta de nuevo', 'danger');
        button.html(default_text);
      })
      .always(() => {
        $('body').waitMe('hide');
      });
  });
});
