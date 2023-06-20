<?php

require __DIR__."/../../src/class/pctremail/EmailSend.php";

$emailSend = new EmailSend();
$emailSend->setMailTo("to@test.fr", "to");
$emailSend->setMailFrom("from@test.fr", "from");
$emailSend->setObjet("test");
$emailSend->setMessageHTML("<h1>Bonjour,</h1>".
    "<table>".
        "<tr>".
            "<th>Company</th>".
            "<th>Contact</th>".
            "<th>Country</th>".
        "</tr>".
        "<tr>".
            "<td>Alfreds Futterkiste</td>".
            "<td>Maria Anders</td>".
            "<td>Germany</td>".
        "</tr>".
        "<tr>".
            "<td>Centro comercial Moctezuma</td>".
            "<td>Francisco Chang</td>".
            "<td>Mexico</td>".
        "</tr>".
    "</table>");
$emailSend->send();