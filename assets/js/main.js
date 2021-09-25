$('document').ready(() => {
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
});
