<?php

use Dompdf\Dompdf;

function get_view($view_name){
  // Si no existe el archivo de la vista que se quiere ver manda el die()
  // La variable VIEWS se declaro en views
  $view = VIEWS.$view_name.'View.php';
  
  if(!is_file($view)) {
    die('La vista no existe');
  }

  // Si existe la vista
  require_once $view;
}

function get_quote() {
  // Si no existe la variable de session 'new_quote' entonces que ejecute el if
  if(!isset($_SESSION['new_quote'])) {

    // Inicializamos la variable de session 'new_quote', con todos sus parámetros
    return $_SESSION['new_quote'] = [
      // Folio con número aleatorio (hecho con la función rand)
      'number' => rand(111111, 999999),
      // Nombre
      'name' => '',
      // Compañía
      'company' => '',
      // Email
      'email' => '',
      // Items para cotizar
      'items' => [],
      // Subtotal
      'subtotal' => 0,
      // Impuestos
      'taxes' => 0,
      // Envío
      'shipping' => 0,
      // Total neto de la cotización
      'total' => 0
    ];
  }
  // Si ya existe Recalcular todos los totales
  recalculate_quote();

  return $_SESSION['new_quote'];
}

function set_client($client){
  // Se crea un arreglo ($_SESSION) con los datos del cliente, para tener presente los datos del cliente el session
  // La variable $client se segmenta por cada campo
  $_SESSION['new_quote']['name'] = trim($client['nombre']);
  $_SESSION['new_quote']['company'] = trim($client['empresa']);
  $_SESSION['new_quote']['email'] = trim($client['email']);
  return true;
}

function recalculate_quote() {
  $items = [];
  $subtotal = 0;
  $taxes = 0;
  $shipping = 0;
  $total = 0;

  if(!isset($_SESSION['new_quote'])){
    return false;
  }

  // Validar items
  $items = $_SESSION['new_quote']['items'];

  // Si la lista de items esta vacía no es necesario calcular nada
  if(!empty($items)){
    foreach ($items as $item) {
      $subtotal += $item['total'];
      $taxes += $item['taxes'];
    }
  }

  $shipping = $_SESSION['new_quote']['shipping'];
  $total = $subtotal + $taxes + $shipping;

  $_SESSION['new_quote']['subtotal'] = $subtotal;
  $_SESSION['new_quote']['taxes'] = $taxes;
  $_SESSION['new_quote']['shipping'] = $shipping;
  $_SESSION['new_quote']['total'] = $total;
  return true;

}

function restart_quote(){
  return $_SESSION['new_quote'] = [
    // Folio con número aleatorio (hecho con la función rand)
    'number' => rand(111111, 999999),
    // Nombre
    'name' => '',
    // Compañía
    'company' => '',
    // Email
    'email' => '',
    // Items para cotizar
    'items' => [],
    // Subtotal
    'subtotal' => 0,
    // Impuestos
    'taxes' => 0,
    // Envío
    'shipping' => 0,
    // Total neto de la cotización
    'total' => 0
  ];

  return true;
}


function get_items(){
  // Inicializamos los items por cualquier cosa
  $items = [];

  // Si no existe la cotización y obviamente esta vacío el array
  if(!isset($_SESSION['new_quote']['items'])){
    // Retorna los items (array vacío)
    return $items;
  }

  // Si ya existen items, entonces se retornan
  $items = $_SESSION['new_quote']['items'];

  return $items;
}

function get_item($id){
  // Pasamos la función de obtener items a la variable $items
  $items = get_items();

  // Si no hay items
  if(empty($items)){
    return false;
  }

  // Si hay items iteramos cada uno de ellos
  foreach($items as $item){
    // Validar si existe con el mismo id pasado
    if($item['id'] === $id){
      // Retornamos el item que se obtuvo
      return $item;
    }
  }

  // No hubo un match o resultados
  return false;
}

function delete_items(){
  // Ingresamos al índice items y lo colocamos en un arreglo vacío
  $_SESSION['new_quote']['items'] = [];

  // Recalculamos para volver a 0 todo
  recalculate_quote();
 
  return true;
}


function delete_item($id){
  // Pasamos la función de obtener items a la variable $items
  $items = get_items();

  // Si no hay items
  if(empty($items)) {
    return false;
  }

  // Si hay items iteramos y obtenemos su índice (del item)
  foreach ($items as $i => $item) {
    // Validar si existe con el mismo id pasado
    if($item['id'] === $id){
      // Eliminamos el item a través de su indice con la función unset()
      unset($_SESSION['new_quote']['items'][$i]);
      // Retornamos true ya que se hizo la eliminación correctamente
      return true;
    }
  }

  // No hubo resultados o match con algún item
  return false;
}

function add_item($item){
  // Pasamos la función de obtener items a la variable $items
  $items = get_items();

  // Si existe el id ya en nuestro items podemos actualizar la información en lugar de agregarlo
  if(get_item($item['id']) !== false){
    // Iteramos en cada uno de los items
    // $e_item es igual al item ya existente
    foreach($items as $i => $e_item){
      // Si el id del item que se mando a la función es igual al ya existente, entonces que lo actualice
      if($item['id'] === $e_item['id']){
        // Entramos a los items y agregamos la actualización del item
        $_SESSION['new_quote']['items'][$i] = $item;
        return true;
      }
    }
  }

  // No existe en la lista, se agrega simplemente
  $_SESSION['new_quote']['items'][] = $item;
  return true;
}

function json_build($status = 200, $data = null, $msg = ''){
  // Si el mensaje no existe entonces que haga el switch para mostrar el mensaje dependiendo del status
  if(empty($msg) || $msg == '') {
    switch($status){
      case 200:
        $msg = 'OK';
        break;
      case 201:
        $msg = 'Created';
        break;
      case 400:
        $msg = 'Invalid Request';
        break;
      case 403:
        $msg = 'Access Denied';
        break;
      case 404:
        $msg = 'Not Found';
        break;
      case 500:
        $msg = 'Internal Server Error';
        break;
      case 550:
        $msg = 'Permission Denied';
        break;
      default:
        break;
    }
  }

  $json = 
  [
    'status' => $status,
    'data' => $data,
    'msg' => $msg
  ];

  // Lo codificamos como json, por lo tanto construye un json conforme a la información que se le manda
  return json_encode($json);
}

function json_output($json){
  // Colocamos los headers para la lectura correctamente
  header('Access-Control-Allow-Origin: *');
  // Se especifica que debe ser un json y el charset uft-8 para que acepte ñ, caracteres, etc
  header('Content-Type: application/json;charset=utf-8');

  // Si pasa un error y pasamos un array, que lo convierta a json
  if(is_array($json)){
    $json = json_encode($json);
  }

  // Imprimimos el json
  echo $json;

  exit();
}

function get_module($view, $data = []) {
  $view = $view.'.php';
  if(!is_file($view)){
    return false;
  }

  // Conversión de la data a json
  // Se pasa el array asociativo ($data = []) a un objeto ({})
  $d = $data = json_decode(json_encode($data));

  // Se inicializa el buffer con ob_start(), absorbe información de forma temporal que no se muestra en la vista
  ob_start();
  require_once $view;

  // El contenido del buffer, lo guardamos en $output
  $output = ob_get_clean();
  return $output;
}

// Ejemplo de una función que empiece con "hook_"
function hook_mi_funcion() {
  echo 'Ejecutar función';
}

// Obtener resumen de cotización
function hook_get_quote_resumen(){

  // Vamos a cargar la cotización
  $quote = get_quote();
  $html = get_module(MODULES.'quote_table', $quote);
  json_output(json_build(200, ['quote' => $quote, 'html' => $html]));
}

// Agregar concepto
function hook_add_to_quote(){
  // Validar que cada campo haya pasado por POST
  // Si no existe alguno de los parámetros, entonces se manda un error 403
  if(!isset($_POST['concepto'], $_POST['tipo'], $_POST['precio_unitario'], $_POST['cantidad'])){
    // Se pasa un json con el error 403
    json_output(json_build(403, null, 'Parámetros incompletos'));
  }

  // Ahora pasamos cada campo que se llego por POST a una variable
  // Cada campo que sea string la pasamos por trim() 
  $concept = trim($_POST['concepto']);
  $type = trim($_POST['tipo']);
  // Pasamos el price a un valor float
  // Igual reemplazamos los $ y , por un espacio vacío para evitar errores con str_replace()
  $price = (float) str_replace([',', '$'], '', $_POST['precio_unitario']);
  // Pasamos la cantidad a un entero
  $quantity = (int) trim($_POST['cantidad']);
  // Pasamos el subtotal a un valor float
  $subtotal = (float) $price * $quantity;
  // Igual los impuestos pasan a un float
  $taxes = (float) $subtotal * (TAXES_RATE / 100);

  $item = [
    'id' => rand(1111, 9999),
    'concept' => $concept,
    'type' => $type,
    'quantity' => $quantity,
    'price' => $price,
    'taxes' => $taxes,
    'total' => $subtotal
  ];

  // El if es para ver si hubo un error al agregar el item
  if(!add_item($item)){
    // Mandamos el error 400 en un JSON
    json_output(json_build(400, null, 'Hubo un problema al guardar el concepto de la cotización'));
  }

  // Si no entro a la condición de arriba, entonces todo paso correctamente
  // Mandamos el 201 que se trata de haberse realizado exitosamente
  // El mensaje de 201 es "Created"
  json_output(json_build(201, get_item($item['id']), 'Concepto agregado correctamente'));
}

// Reiniciar la cotización
function hook_restart_quote(){
  $items = get_items();

  if(empty($items)){
    json_output(json_build(400, null, 'No es necesario reiniciar la cotización, no hay conceptos en ella'));
  }

  if(!restart_quote()){
    json_output(json_build(400, null, 'Hubo un problema al reiniciar la cotización'));
  }

  json_output(json_build(200, get_quote(), 'La cotización se ha reiniciado con éxito'));
}

// Borrar un concepto de la cotización
function hook_delete_concept() {
  // Si no existe el id por POST que mande el error 403
  if(!isset($_POST['id'])){
    json_output(json_build(403, null, 'Parámetros incompletos'));
  }

  // Usamos el método delete_item para borrar el concepto y si regresa false (hubo error) manda el error 400
  if(!delete_item((int) $_POST['id'])){
    json_output(json_build(400, null, 'Hubo un problema al borrar el concepto'));
  }

  // Si no hubo error, entonces se recalcula la cotización
  json_output(json_build(200, get_quote(), 'Concepto borrado con éxito'));
}

// Cargar un concepto para editar
function hook_edit_concept(){
  // Si no existe el id por POST que mande el error 403
  if(!isset($_POST['id'])){
    json_output(json_build(403, null, 'Parámetos incompletos'));
  }

  // Se obtiene el item por el método get_item() a través de su id
  if(!$item = get_item((int) $_POST['id'])){
    // Si hubo error al obtener el item, se manda el error 400
    json_output(json_build(400, null, 'Hubo un problema al cargar el concepto'));
  }

  // Si no hubo error, entonces se manda el item
  json_output(json_build(200, $item, 'Concepto cargado con éxito'));
}

// Guardar los cambios de un concepto
function hook_save_concept(){
  // Validar los campos del concepto
  if(!isset($_POST['id_concepto'], $_POST['concepto'], $_POST['tipo'], $_POST['precio_unitario'], $_POST['cantidad'])){
    json_output(json_build(403, null, 'Parámetros incompletos'));
  }

  $id = (int) $_POST['id_concepto'];
  $concept = trim($_POST['concepto']);
  $type = trim($_POST['tipo']);
  $price = (float) str_replace([',', '$'], '', $_POST['precio_unitario']);
  $quantity = (int) trim($_POST['cantidad']);
  $subtotal = (float) $price * $quantity;
  $taxes = (float) $subtotal * (TAXES_RATE / 100);

  $item = [
    'id' => $id,
    'concept' => $concept,
    'type' => $type,
    'quantity' => $quantity,
    'price' => $price,
    'taxes' => $taxes,
    'total' => $subtotal
  ];

  if(!add_item($item)){
    json_output(json_build(400, null, 'Hubo un problema al guardar los cambios del concepto'));
  }

  json_output(json_build(200, get_item($id), 'Cambios guardados con éxito'));
}

// Generar el PDF
function generate_pdf($filename = null, $html, $save_to_file = true){
  // Se le coloca un numbre por defecto
  $filename = $filename === null ? time().'.pdf' : $filename.'.pdf';
  
  // Creamos la instancia del método para generar el PDF
  $pdf = new Dompdf();
  // El tipo de papel
  $pdf->setPaper('A4');
  // Se le pasa el HTML que se quiere pasar a PDF
  $pdf->loadHtml($html);
  // Se renderiza el PDF
  $pdf->render();
  
  
  // Si $save_to_file es true me guarda un archivo, si es false me saca el PDF para descargar
  if($save_to_file){
    // Guardamos toda la información binaria del PDF en la variable $output
    $output = $pdf->output();
    // Colocamos esa información y el nombre del PDF en un archivo
    file_put_contents($filename, $output);
    return true;
  }

  // Se descarga el PDF
  $pdf->stream($filename);
  return true;
}

// Crear el PDF de la cotización
function hook_generate_pdf($filename = null, $html, $save_to_file = true){
  // Validar que hayan llegado los parámetros
  if(!isset($_POST['nombre'], $_POST['empresa'], $_POST['email'])){
    json_output(json_build(403, null, 'Parámetos incompletos'));
  }

  // Validar correo con filter_var que es propia de PHP
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    json_output(json_build(400, null, 'Dirección de correo no válida'));
  }

  // Se construye un arreglo con los datos del cliente
  $client = [
    'nombre' => $_POST['nombre'],
    'empresa' => $_POST['empresa'],
    'email' => $_POST['email']
  ];
  // Se pasan a la función set_client
  set_client($client);

  // Cargar cotización
  $quote = get_quote();

  // Si esta vacía la cotización (sin items) mejor que no pase
  if(empty($quote['items'])){
    json_output(json_build(400, null, 'No hay conceptos en la cotización'));
  }

  // Se crea un modulo
  $module = MODULES.'pdf_template';
  // Se usa la función get_module que se creo arriba, para pasarle el modulo y la cotización
  $html = get_module($module, $quote);
  // Se le coloca un nombre a la cotización, donde se le concatena el número de cotización
  $filename = 'cotizacion_'.$quote['number'];
  // Se le pasa la dirección donde se podra dicha cotización
  $download = URL.UPLOADS.$filename;
  // Se accede a la quote y se le agrega al url dicha dirección
  $quote['url'] = $download;

  // Generar PDF y guardarlo en servidor
  if(!generate_pdf(UPLOADS, $filename, $html)){
    json_output(json_build(400, null, 'Hubo un problema al generar la cotización'));
  }

  // Si todo pasa bien, se mandara dicha cotización
  json_output(json_build(200, $quote, 'Cotización guardada con éxito'));
}