<?php
require __DIR__ . "/../src/class/pctremail/MessageEmail.php";

try {
    $obj = new MessageEmail(__DIR__ . "/messages.json");
    $obj->addVar("TEST_V1", "le test");
    $obj->recupeMessage("message_1");
    echo $obj->getMessage();
} catch (Throwable $th) {
    echo $th->getMessage()."<br />";
}