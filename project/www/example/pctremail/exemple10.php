<?php

require __DIR__."/../../src/class/pctremail/EmailSend.php";

$emailSend = new EmailSend();
$emailSend->setMailTo("to@test.fr");
$emailSend->setMailFrom("from@test.fr");
$emailSend->setCharset("UTF-8");
$emailSend->setObjet("test");
$emailSend->setMessageText("test");
$emailSend->send();
