<?php

require __DIR__."/../../src/class/pctremail/EmailSend.php";

try {
    $emailSend = new EmailSend();
    $emailSend->setMailTo("to@test.fr");
    $emailSend->setMailFrom("from@test.fr");
    $emailSend->setObjet("test");
    $emailSend->setMessageText("test");
    $emailSend->send();
} catch (Throwable $th) {
    echo $th->getMessage();
}