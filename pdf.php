<?php 
// Configuración para traer el autoload de los paquetes de composer
require_once 'app/config.php';

// Ejemplo de generar PDF
// Namespace de la librería para el PDF
// use Dompdf\Dompdf;

// $pdf = new Dompdf();

// $pdf->loadHtml('<h1 style="background: red;">Hola mundo, este es mi primer PDF</h1>');

// $pdf->setPaper('A4');

// $pdf->render();

// $pdf->stream(time().'.pdf');

generate_pdf('cotizacion_'.time(), get_module(MODULES.'pdf_template'));