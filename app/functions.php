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