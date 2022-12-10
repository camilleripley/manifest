<?php

// dz 7.5

$folderName = "./test_search"; // в какой папке ищем
$fileName   = "test.txt";   // что ищем

// Находим нужные файлы
$found = search_file( $folderName, $fileName );

// Фильтруем, убирая из массива нулевые файлы
$found = array_filter($found, 'del_empty');

if ($found) {
    print_r( $found );
} else {
    echo "Поиск не дал результатов!";
}

// Функия рекурсивного поиска файла по папке и всем ее подпапкам
function search_file( $folderName, $fileName ){
    $found = array();
    $folderName = rtrim( $folderName, '/' );

    $dir = opendir( $folderName ); // открываем текущую папку

    // перебираем папку, пока есть файлы
    while( ($file = readdir($dir)) !== false ){
        $file_path = "$folderName/$file";

        if( $file == '.' || $file == '..' ) continue;

        // это файл проверяем имя
        if( is_file($file_path) ){
            // если имя файла искомое, то вернем путь до него
            if( false !== strpos($file, $fileName) ) $found[] = ['fPath' => $file_path, 'fSize' => filesize($file_path)];
        }
        // это папка, то рекурсивно вызываем search_file
        elseif( is_dir($file_path) ){
            $res = search_file( $file_path, $fileName );
            $found = array_merge( $found, $res );
        }

    }

    closedir($dir); // закрываем папку

    return $found;
}

// Функция фильтрации результатов поиска по размеру, оставить только файлы ненулевого размера
function del_empty ($v) : bool {
    if ($v['fSize'] == 0) {
        return false;
    }
    return true;
}