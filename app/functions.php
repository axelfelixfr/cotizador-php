<?php

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
  foreach ($items as $i -> $item) {
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
    foreach($items as $i -> $e_item){
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

function json_output(){
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

  return true;
}