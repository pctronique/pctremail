<?php
if(!empty($_POST)) {
    header('Location: ./');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            padding:0 5px;
            margin:0;
            /*width:100%;*/
        }
        form {
            display: grid;
            justify-content: start;
            align-items: center;
        }
        h3 {
            padding-top:10px;
        }
        h3, #objet, #message, button, input[type="File"] {
            grid-column: 1 / 6;
        }
        .email {
            grid-column: 1 / 3;
            width: 90%;
        }
        .email ~ input {
            grid-column: 3 / 3;
        }
        .email ~ input ~ label {
            grid-column: 4 / 4;
        }
        .email ~ input ~ label ~ input {
            grid-column: 5 / 5;
        }
        input[type="radio"] {
            grid-column: 1 / 1;
        }
        input[type="radio"] label {
            grid-column: 2 / 2;
        }
        input[type="radio"] ~ select {
            grid-column: 3 / 6;
        }
        button {
            justify-items: center;
        }
        @media screen and (max-width: 320px) {
            h3, #objet, #message, button, input[type="File"] {
                grid-column: 1 / 4;
            }
            .email ~ input ~ label {
                grid-column: 1 / 3;
            }
            .email ~ input ~ label ~ input {
                grid-column: 4 / 4;
            }
            input[type="radio"] ~ select {
                grid-column: 3 / 4;
            }
        }
    </style>
</head>
<body>
    <form action="./" method="post">
        <h3>Destinataire</h3>
        <label for="email_dest" class="email">email</label><input type="email" name="email_dest" id="email_dest" require>
        <label for="name_dest">nom</label><input type="text" name="name_dest" id="name_dest" require>
        <h3>Expéditeur</h3>
        <label for="email_expe" class="email">email</label><input type="email" name="email_expe" id="email_expe">
        <label for="name_expe">nom</label><input type="text" name="name_expe" id="name_expe">
        <h3>Pièces jointes</h3>
        <input type="File" name="fileToUploadAll[]" id="fileToUploadAll" accept="image/png, image/jpeg" multiple />
        <h3>Contenu enregistré</h3>
        <input type="radio" name="text" id="text-non"><label for="text-non">Non</label>
        <option value=""></option>
        </select>
        <input type="radio" name="text" id="text-ini"><label for="text-ini">Fichier ini</label><select name="contenu-ini" id="contenu-ini">
        <option value=""></option>
        </select>
        <input type="radio" name="text" id="text-json"><label for="text-json">Fichier json</label><select name="contenu-json" id="contenu-json">
        <option value=""></option>
        </select>
        <h3>Objet</h3>
        <input type="text" name="objet" id="objet">
        <h3>Message</h3>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <button>Envoyer</button>
    </form>
</body>
</html>