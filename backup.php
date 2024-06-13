<?php
function zipDirectory($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = realpath($source);
    if (is_dir($source)) {
        $iterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {
            $file = realpath($file);
            if (is_dir($file)) {
                $zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
            } else if (is_file($file)) {
                $zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source)) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

$sourceSystem = 'C:/xampp/htdocs/recuento';
$destinationSystem = 'C:/Users/Claudio/Desktop/recuento_backup_system.zip';

$database = 'recuento';
$user = 'root';
$password = ''; // Coloca tu contraseña aquí si es necesario
$host = 'localhost';
$destinationDatabase = 'C:/Users/Claudio/Desktop/recuento_backup_db.sql';

$backupSuccess = true;

// Realizar copia de seguridad de la base de datos usando mysqldump
$command = "C:/xampp/mysql/bin/mysqldump --host=$host --user=$user --password=$password $database > $destinationDatabase";
exec($command, $output, $return_var);
if ($return_var !== 0) {
    echo 'Error al realizar el backup de la base de datos.';
    $backupSuccess = false;
}

// Realizar copia de seguridad del sistema
if ($backupSuccess && !zipDirectory($sourceSystem, $destinationSystem)) {
    echo 'Error al realizar el backup del sistema.';
    $backupSuccess = false;
}

if ($backupSuccess) {
    echo 'Backup realizado con éxito.';
}
?>
