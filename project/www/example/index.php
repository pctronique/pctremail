<?php
require __DIR__ . "/../src/class/pctremail/MessageEmail.php";
require __DIR__ . "/../src/class/pctremail/EmailSend.php";

$msgIni = new MessageEmail(__DIR__."/message.ini");
$msgJson = new MessageEmail(__DIR__."/messages.json");
$msgIni->addVar("NAME", "Pctremail")->addVar("USER", "pctronique");
$msgJson->addVar("NAME", "Pctremail")->addVar("USER", "pctronique");

if (!empty($_POST)) {
    header('Location: ./');
}

var_dump($msgIni->getKeys());
var_dump($msgJson->getKeys());

$type_info = "info-no";
$info_txt = "";

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
                overflow-x: hidden;
            }

            body {
                min-width: 330px;
                max-width: 100vw;
                min-height: 100vh;
                background-color: black;
            }

            form * {
                padding: 0 5px;
            }

            h1 {
                text-align: center;
                margin-top: 15px;
            }

            .info-no {
                display: none;
            }
            .info-err {
                display: none;
            }
            .info-err {
                display: val;
            }

            form {
                margin-top: 15px;
                margin-left: auto;
                margin-right: auto;
                padding: 10px;
                max-width: 480px;
                display: grid;
                justify-content: start;
                align-items: center;
                justify-items: stretch;
                align-content: center;
                box-sizing: border-box;
                overflow-x: hidden;
                background-color: #f3dec4;
                border-radius: 10px;
                border: yellow 2px solid;
            }

            h3 {
                padding-top: 10px;
            }

            h1,
            h3,
            #objet,
            #message,
            button,
            input[type="File"] {
                grid-column: 1 / 6;
            }

            .email {
                grid-column: 1 / 3;
            }

            .email-txt {
                grid-column: 3 / 4;
            }

            .name {
                grid-column: 4 / 5;
            }

            .name-expe {
                grid-column: 5 / 6;
            }

            input[type="radio"] {
                grid-column: 1 / 2;
            }

            input[type="radio"] label {
                grid-column: 2 / 3;
            }

            input[type="radio"]~select {
                grid-column: 3 / 6;
            }

            button {
                justify-items: center;
                margin-top:10px;
            }

            #text-ini ~ #contenu-ini, #text-json ~ #contenu-json {
                display: none;
            }

            #text-ini:checked ~ #contenu-ini, #text-json:checked ~ #contenu-json {
                display: block;
            }

            @media screen and (max-width: 606px) {
                form {
                    max-width: 328px;
                }

                h3,
                #objet,
                #message,
                button,
                input[type="File"] {
                    grid-column: 1 / 4;
                }

                .name {
                    grid-column: 1 / 3;
                }

                .name-expe {
                    grid-column: 4 / 4;
                }

                input[type="radio"]~select {
                    grid-column: 3 / 4;
                }
            }
            @media screen and (max-width: 330px) {
                form {
                    margin-top: 1px;
                }
            }
        </style>
    </head>

    <body>
        <form action="./" method="post">
            <p class="info <?= $type_info ?>"><?= $info_txt ?></p>
            <h1>Pctremail</h1>
            <h3>Destinataire</h3>
            <label for="email-dest" class="email">email</label><input type="email" class="email-txt" name="email-dest" id="email-dest" require>
            <label for="name-dest" class="name">nom</label><input type="text" name="name-dest" class="name-txt" id="name-dest" require>
            <h3>Expéditeur</h3>
            <label for="email-expe" class="email">email</label><input type="email" class="email-txt" name="email-expe" id="email-expe">
            <label for="name-expe" class="name">nom</label><input type="text" name="name-expe" class="name-txt" id="name-expe">
            <h3>Pièces jointes</h3>
            <input type="File" name="fileToUploadAll[]" id="fileToUploadAll" accept="image/png, image/jpeg" multiple />
            <h3>Contenu enregistré</h3>
            <input type="radio" name="text" id="text-non" checked><label for="text-non">Non</label>
            <input type="radio" name="text" id="text-ini"><label for="text-ini">Fichier ini</label><select name="contenu-ini" id="contenu-ini">
                <option value="">--Veuillez choisir une option--</option>
                <?php foreach ($msgIni->getKeys() as $value) { ?>
                    <option value="<?= $value ?>"><?= $value ?></option>
                <?php } ?>
            </select>
            <input type="radio" name="text" id="text-json"><label for="text-json">Fichier json</label><select name="contenu-json" id="contenu-json">
                <option value="">--Veuillez choisir une option--</option>
                <?php foreach ($msgJson->getKeys() as $value) { ?>
                    <option value="<?= $value ?>"><?= $value ?></option>
                <?php } ?>
            </select>
            <h3>Objet</h3>
            <input type="text" name="objet" id="objet">
            <h3>Message</h3>
            <textarea name="message" id="message" cols="30" rows="10"></textarea>
            <button>Envoyer</button>
        </form>
    </body>

</html>