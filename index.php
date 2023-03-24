<?php

backupDB();

function connectToDatabase($dbName){
    return new mysqli("localhost", "root", "", $dbName);
}


function createDirectory($path){
    mkdir($path, true);
}

function createFile($path,$fileName){
    $time = time();
    return fopen("{$path}/{$fileName}_{$time}.txt", 'w');
}


function backupDB(){
    $backupDir = "C:/NWP_LV2_backup";
    $dbName = "new_lv2";
    createDirectory($backupDir);
    $connection = connectToDatabase($dbName);
    $file = createFile($backupDir, $dbName);
    $tables_res = mysqli_query($connection, 'SHOW TABLES');
    echo 'lol2';
    if (mysqli_num_rows($tables_res) > 0) {
        echo 'lol';
        while (list($table) = mysqli_fetch_array($tables_res, MYSQLI_NUM)) {
            backupTable($connection, $file, $table);
        }
    }
    fclose($file);
}

function backupTable($connection, $file, $tableName){
    echo "\r\n\n";
    echo $tableName;
    $query = "SELECT * FROM {$tableName}";
    echo "\r\n\n";
    echo $query;
    $table_res = mysqli_query($connection, $query);
    $columns = $table_res->fetch_fields();
    if (mysqli_num_rows($table_res) > 0) {
        while ($row = mysqli_fetch_array($table_res, MYSQLI_NUM)){
            fwrite($file, "INSERT INTO $tableName (");
            
            $size = count($columns);
            for($i = 0; $i < $size - 1; $i++) {
                fwrite($file, "{$columns[$i]->name},");
            }
            fwrite($file, "{$columns[$size - 1]->name})\r\nVALUES (");

            
            for($i = 0; $i < $size - 1; $i++) {
                $value = addslashes($row[$i]);
                fwrite ($file, "'$value',");
            }
            $value = addslashes($row[$size - 1]);
            fwrite ($file, "'$value'");

            fwrite($file, ");\r\n");
        }
    }
}


?>