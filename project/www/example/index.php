<?php
require __DIR__ . "/../src/class/pctremail/EmailSend.php";

try {
    $emailSend1 = new EmailSend();
    $emailSend1->setMailTo("to@test.fr", "to");
    $emailSend1->setMailFrom("from@test.fr", "from");
    $emailSend1->setObjet("test");
    $emailSend1->setMessageText(__DIR__."/test.html");
    $emailSend1->addAttachment(__DIR__."/../favicon.ico");
    $emailSend1->addAttachment(__DIR__."/../favicon.ico");
    $emailSend1->send();
} catch (Throwable $th) {
    echo $th->getMessage()."<br />";
}
/*$emailSend1->setMailTo("to@test.fr", "to");
$emailSend1->setMailFrom("from@test.fr", "from");
$emailSend1->setObjet("test");
$emailSend1->setMessageHTML(__DIR__."/test.html");
$emailSend1->addAttachment(__DIR__."/../favicon.ico");
$emailSend1->addAttachment(__DIR__."/../favicon.ico");
$emailSend1->send();*/
echo "test 0251";