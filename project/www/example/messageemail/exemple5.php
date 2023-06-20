<?php
require __DIR__ . "/../../src/class/pctremail/MessageEmail.php";

$msgEmail = new MessageEmail();

$msgEmail->addVar("NAME", "Pctremail")
        ->addVar("USER", "pctronique");

echo "Objet : ".$msgEmail->object("Exemple 1 ({{NAME}})")."<br />";
echo "Message : ".$msgEmail->message("./test.html");
