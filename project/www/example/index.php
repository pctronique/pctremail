<?php
require __DIR__ . "/../src/class/pctremail/EmailSend.php";

$emailSend = new EmailSend();
$emailSend->setMailTo("to@test.fr", "to");
$emailSend->setMailFrom("from@test.fr", "from");
$emailSend->setObjet("test");
$emailSend->setMessageHTML("test");
$emailSend->addAttachment(__DIR__."/index.php");
$emailSend->send();
