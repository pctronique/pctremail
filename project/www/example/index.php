<?php
require __DIR__ . "/../src/class/pctremail/EmailSend.php";

/*$emailSend = new EmailSend();
$emailSend->setMailTo("to@test.fr", "to");
$emailSend->setMailFrom("from@test.fr", "from");
$emailSend->setObjet("test");
$emailSend->setMessageHTML("test");
$emailSend->addAttachment(__DIR__."/index.php");
$emailSend->send();

$emailSend1 = new EmailSend();
$emailSend1->setMailTo("to@test.fr", "to");
$emailSend1->setMailFrom("from@test.fr", "from");
$emailSend1->setObjet("test");
$emailSend1->setMessageHTML(__DIR__."/test.html");
$emailSend1->addAttachment(__DIR__."/favicon.ico");
$emailSend1->send();*/

$emailSend2 = new EmailSend();
$emailSend2->setMailTo("to@test.fr", "to");
$emailSend2->setMailFrom("from@test.fr", "from");
$emailSend2->setObjet("test");
$emailSend2->setMessageHTML(__DIR__."/favicon.ico");
$emailSend2->send();