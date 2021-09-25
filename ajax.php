<?php

require_once 'app/config.php';

// Parámetro action enviado desde nuestro frontend (JavaScript)
// Debe ser recibido en ajax.php
// Validaremos que el valor de action, concuerde con el nombre de una función
// si existe la función, se ejecuta dicha función y listo
// en caso de no existir la función o no recibir el parámetro
// por defecto mandaremos un error 403 de 'acceso no autorizado'

try {
  // Verifica que exista la acción por POST o GET
  if(!isset($_POST['action']) && !isset($_GET['action'])){
    // Lanza la exception que atrapa el catch
    throw new Exception("El acceso no está autorizado");
  }

  // Guardar el valor de action
  // Dependiendo del método que se envío la acción se le asigna POST o GET
  $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
  // Reemplaza los '-' por '_' ya que pueden existir errores, ya que en las funciones no es posible usar '-'
  $action = str_replace('-', '_', $action);
  // Agregarle la palabra 'hook', por su parte el '%s' funciona para pasar el string de la función
  $function = sprintf('hook_%s', $action); // hook_mi_funcion

  // Validar la existencia de la función
  if(!function_exists($function)){
    // Lanza la exception que atrapa el catch
    throw new Exception("El acceso no está autorizado");
  }

  // Se ejecuta la función
  $function();

} catch (Exception $e) {
  // Hacemos uso de json_output que viene en las funciones definidas en function.php
  json_output(json_build(403, null, $e->getMessage()));
}