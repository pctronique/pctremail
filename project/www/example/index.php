<?php
require __DIR__ . "/../src/class/pctremail/MessageEmail.php";
require __DIR__ . "/../src/class/pctremail/EmailSend.php";

$msgIni = new MessageEmail(__DIR__."/messages.ini");
$msgJson = new MessageEmail(__DIR__."/messages.json");

$tabData = $contentJson = json_decode(file_get_contents(__DIR__."/dataVar.json"), true);
foreach ($tabData as $key => $value) {
    $msgIni->addVar($key, $value);
    $msgJson->addVar($key, $value);
}

if (!empty($_POST)) {

    $upload = __DIR__."/../upload/";
    if(!is_dir($upload)) {
        mkdir($upload, 0777, true);
    }

    $id_error = 0;
    $info = "Le message a été envoyé.";

    try {
        $emailSend1 = new EmailSend();
        $emailSend1->setMailTo($_POST['email-dest'], $_POST['name-dest']);
        $emailSend1->setMailFrom($_POST['email-expe'], $_POST['name-expe']);
        $emailSend1->setObjet($_POST['objet']);
        $emailSend1->setMessageText($_POST['message']);
        if(!empty($_FILES) && array_key_exists('file', $_FILES) && !empty($_FILES['file']['name'])) {
            foreach ($_FILES["file"]["name"] as $key => $name) {
                if(!empty($_FILES['file']['name'][$key])) {
                    if(move_uploaded_file($_FILES['file']['tmp_name'][$key], $upload.$name)) {
                        $emailSend1->addAttachment($upload.$name);
                    } else {
                        throw new Error("Erreur lors du téléchargement du fichier ".$name.".");
                    }
                }
            }
        }
        $emailSend1->send();
    } catch (Throwable $th) {
        $id_error = 1;
        $info = $th->getMessage();
    }

    header('Location: ./?id_error='.$id_error.'&info='.$info);
}

$type_info = "info-no";
$info_txt = "";

if(!empty($_GET) && array_key_exists("id_error", $_GET)) {
    if($_GET['id_error'] == 0) {
        $type_info = "info-val";
    } else {
        $type_info = "info-err";
    }
    $info_txt = array_key_exists("info", $_GET) ? $_GET['info'] : "";
}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="./style.css" />
    </head>

    <body>
        <form action="./" method="post" enctype="multipart/form-data">
            <p class="info <?= $type_info ?>"><?= $info_txt ?></p>
            <h1>Pctremail</h1>
            <h3>Destinataire</h3>
            <label for="email-dest" class="email">email</label><input type="email" class="email-txt" name="email-dest" id="email-dest" require>
            <label for="name-dest" class="name">nom</label><input type="text" name="name-dest" class="name-txt" id="name-dest" require>
            <h3>Expéditeur</h3>
            <label for="email-expe" class="email">email</label><input type="email" class="email-txt" name="email-expe" id="email-expe">
            <label for="name-expe" class="name">nom</label><input type="text" name="name-expe" class="name-txt" id="name-expe">
            <h3>Pièces jointes</h3>
            <input type="File" name="file[]" id="file" accept="image/png, image/jpeg" multiple />
            <h3>Contenu enregistré</h3>
            <input type="radio" name="text" id="text-non" checked><label for="text-non">Non</label>
            <input type="radio" name="text" id="text-ini"><label for="text-ini">Fichier ini</label><select name="contenu-ini" id="contenu-ini">
                <option value="">--Veuillez choisir une option--</option>
                <?php foreach ($msgIni->getKeys() as $value) { ?>
                    <option value="<?= $value ?>"><?= str_replace("_", " ", $value) ?></option>
                <?php } ?>
            </select>
            <input type="radio" name="text" id="text-json"><label for="text-json">Fichier json</label><select name="contenu-json" id="contenu-json">
                <option value="">--Veuillez choisir une option--</option>
                <?php foreach ($msgJson->getKeys() as $value) { ?>
                    <option value="<?= $value ?>"><?= str_replace("_", " ", $value) ?></option>
                <?php } ?>
            </select>
            <h3>Objet</h3>
            <input type="text" name="objet" id="objet">
            <h3>Message</h3>
            <textarea name="message" id="message" cols="30" rows="10"></textarea>
            <button>Envoyer</button>
        </form>
        <script src="./message.js"></script>
    </body>
</html>