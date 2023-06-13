<?php 
include_once 'config_path.php';
include_once RACINE_WWW.'src/config/config_default.php';

$title = "ERROR INCONNU";
$img = "";
$text = "Une erreur est survenu sur la page.";

$key = "0";
if(!empty($_GET) && array_key_exists('error', $_GET) && !empty($_GET['error'])) {
    $key = $_GET['error'];
}


$ini_array = parse_ini_file(dirname(__FILE__) . '/src/includes/message.ini', true);
if(!empty($ini_array) && array_key_exists($key, $ini_array)) {
    $title = $ini_array[$key]['title_erreur'];
    if(!empty($ini_array[$key]['img_erreur'])) {
        $img = "<img src=\"".RACINE."error/src/img/".$ini_array[$key]['img_erreur']."\" alt=\"error\" />";
    }
    $text = $ini_array[$key]['txt_erreur'];
}

$html = file_get_contents(dirname(__FILE__) . '/src/templates/error.html', true);
$html = str_replace("[##RACINE##]", RACINE, $html);
$html = str_replace("[##RACINE_ERROR##]", RACINE_ERROR, $html);
$html = str_replace("[##TITLE_ERROR##]", $title, $html);
$html = str_replace("[##IMG_ERROR##]", $img, $html);
$html = str_replace("[##TEXT_ERROR##]", $text, $html);
echo $html;

?>

