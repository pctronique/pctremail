<?php
require __DIR__ . "/../../src/class/pctremail/MessageEmail.php";

$msgEmail = new MessageEmail("./messages.json");

$msgEmail->addVar("NAME", "Pctremail")
        ->addVar("USER", "pctronique");

// récupération du texte dans la liste des messages.
$msgEmail->recupeMessage('message_2');

echo "Objet : ".$msgEmail->getObject()."<br />";
echo "Message : ".$msgEmail->getMessage();
