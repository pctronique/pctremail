<?php

require __DIR__."/../../src/class/pctremail/EmailSend.php";

$emailSend = new EmailSend();

$emailSend->setFormatVar("[#%s#]");
$emailSend->addVar("NAME", "Pctremail")
        ->addVar("USER", "pctronique");

$emailSend->setMailTo("to@test.fr");
$emailSend->setMailFrom("from@test.fr");
$emailSend->setObjet("Exemple 1 ([#NAME#])");
$emailSend->setMessageText("Madame, Monsieur,\n\n".
                            "Ceci est l'exemple 1 de message à envoyer avec l'utilisation de [#NAME#] de [#USER#].\n\n".
                            "Cordialement.");
$emailSend->send();
