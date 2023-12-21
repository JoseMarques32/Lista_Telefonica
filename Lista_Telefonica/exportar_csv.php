<?php

// Exportar todos os arquivos em CSV

use classdatabase\Database;

require_once('config.php');
require_once('libraries/database.php');

$database = new Database(MYSQL_CONFIG);

 /// Pegando os resultados 
$results = $database ->execute_query("SELECT * FROM contactos");
$rows = $results -> results;

/// Arquivo CSV
$filename = 'contactos'. time(). '.csv';

$file = fopen($filename,'w');

$headers = [];
foreach($rows[0] as $key => $value){
    $headers[] = $key;
}

fputcsv($file, $headers);

$tmp = [];
foreach($rows as $row){
    $obj = (array)$row;
    fputcsv($file,$obj);
}
fclose($file);

/// Download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-lenght: ' . filesize($filename));
readfile($filename);
