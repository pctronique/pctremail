<?php

require __DIR__ . "/../src/class/pctremail/MessageEmail.php";
require __DIR__ . "/../src/class/pctremail/EmailSend.php";


if (!(!empty($_POST) && array_key_exists("keyMess", $_POST) && array_key_exists("type", $_POST) && !empty($_POST['keyMess']) && !empty($_POST['type']))) {
    die("Vous ne pouvez pas faire cette action");
}

$msgEmail = new MessageEmail(__DIR__."/messages.ini");
if($_POST['type'] == "json") {
    $msgEmail = new MessageEmail(__DIR__."/messages.json");
}

$tabData = json_decode(file_get_contents(__DIR__."/dataVar.json"), true);
foreach ($tabData as $key => $value) {
    $msgEmail->addVar($key, $value);
}

$msgEmail->recupeMessage($_POST['keyMess']);
$dateResultat = [
    "obj" => $msgEmail->getObject(),
    "message" => $msgEmail->getMessage()
];

echo "true"."[#DATA#]".json_encode($dateResultat);
