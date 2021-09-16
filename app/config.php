<?php

// Variables

// Para saber si estamos en servidor local
define('IS_LOCAL', in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
define('URL', (IS_LOCAL ? 'http://127.0.0.1:8848/cotizador' : 'LA URL DEL SERVIDOR DE PRODUCCIÓN'));

// Rutas para carpetas
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', getcwd().DS);
define('APP', ROOT.'app'.DS);
define('ASSETS', ROOT.'assets'.DS);
define('TEMPLATES', ROOT.'templates'.DS);
define('INCLUDES', TEMPLATES.'includes'.DS);
define('MODULES', TEMPLATES.'modules'.DS);
define('VIEWS', TEMPLATES.'views'.DS);
define('UPLOADS', ROOT.'uploads'.DS);

// Para archivos que vayamos a incluir en header o footer (css o js)
define('CSS', URL.'assets/css/');
define('IMG', URL.'assets/img/');
define('JS', URL.'assets/js/');

// Cargar todas las funciones
require_once APP.'functions.php';
