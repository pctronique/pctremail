<?php
require __DIR__ . "/../../src/class/pctremail/MessageEmail.php";

try {
    $msgEmail = new MessageEmail("./messages.ini");

    $msgEmail->addVar("NAME", "Pctremail")
            ->addVar("USER", "pctronique");

    echo "Objet : ".$msgEmail->object("Exemple 1 ({{NAME}})")."<br />";
    echo "Message : ".$msgEmail->message("Madame, Monsieur,"."<br />"."<br />".
                                        "Ceci est l'exemple 1 de message à envoyer avec l'utilisation de {{NAME}} de {{USER}}."."<br />"."<br />".
                                        "Cordialement.");
} catch (Throwable $th) {
    echo $th->getMessage();
}