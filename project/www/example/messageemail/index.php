<?php

$cdir = scandir(__DIR__);
foreach ($cdir as $value) {
    if (!in_array($value,array(".",".."))) {
        if(is_file(__DIR__ . DIRECTORY_SEPARATOR . $value)) {
            if(($mime = mime_content_type(__DIR__ . DIRECTORY_SEPARATOR . $value))!==false && strtolower($mime) == 'text/x-php' && strtolower($value) != "index.php") {
                echo '<a href="./'.$value.'">'.$value.'</a><br />';
            }
        }
    }
}