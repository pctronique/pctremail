<?php
use PHPUnit\Framework\TestCase;

define("RACINE_UNIT", dirname(__FILE__)."/../..");
require_once(RACINE_UNIT . '/config_path.php');
require_once(RACINE_UNIT . '/function_test.php');
require_once(RACINE_WWW . '/src/class/pctremail/EmailSend.php');

/**
 * ClassNameTest
 * @group group
 */
class EmailSendTest extends TestCase
{
    /**
     * @var Create_folder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->object = new EmailSend();
    }

        /**
         * Set the value of mail
         */
        public function testSetMailTo(string|null $mail_to, string|null $name_to = null): self
        {
            $this->mail_to = !empty($mail_to) ? $mail_to : "";
            $this->name_to = !empty($name_to) ? $name_to : "";
            if (!filter_var($mail_to, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".$mail_to.") n'est pas valide.", 36245000001);
            }
            return $this;
        }

        /**
         * Set the value of mail_from
         */
        public function testSetMailFrom(string|null $mail_from, string|null $name_from = null): self
        {
            $this->mail_from = !empty($mail_from) ? $mail_from : "";
            $this->name_from = !empty($name_from) ? $name_from : "";
            if (!filter_var($mail_from, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".$mail_from.") n'est pas valide.", 36245000002);
            }
            return $this;
        }

        /**
         * Set the value of charset
         */
        public function testSetCharset(string|null $charset): self
        {
            $this->charset = !empty($charset) ? $charset : "";
            return $this;
        }

        /**
         * Set the value of objet
         */
        public function testSetObjet(string|null $objet): self
        {
            $this->objet = !empty($objet) ? $objet : "";
            return $this;
        }

        /**
         * Set the value of messageHTML
         */
        public function testSetMessageHTML(string|null $messageHTML): self
        {
            $this->messageHTML = $this->textAndFile($messageHTML);
            return $this;
        }

        /**
         * Set the value of messageText
         */
        public function testSetMessageText(string|null $messageText): self
        {
            $this->messageText = $this->textAndFile($messageText);
            return $this;
        }

        /**
         * Set the value of attachment
         */
        public function testAddAttachment(string|null $file): self
        {
            if(empty($file)) {
                return $this;
            }
            if(!is_file($file)) {
                throw new Error("Le fichier (".$file.") n'est pas valide.", 36245000003);
            }
            array_push($this->attachments, $file);
            return $this;
        }

        /**
         * Pour envoyer un message html (si le message ne peut pas etre lut en html, il sera affiche en texte).
         * Si le message texte est vide, il sera remplacer par le htlm (sans les balises).
         */
        public function testSend():self {
            $mail_from_def = "\"" . $this->mail_from . "\" <" . $this->mail_from . ">";
            if(!empty($this->name_from)) {
                $mail_from_def = "\"" . $this->name_from . "\" <" . $this->mail_from . ">";
            }
            $mail_to_def = "\"" . $this->mail_to . "\" <" . $this->mail_to . ">";
            if(!empty($this->name_to)) {
                $mail_to_def = "\"" . $this->name_to . "\" <" . $this->mail_to . ">";
            }
            if (!filter_var($this->mail_to, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email du destinataire (".$this->mail_to.") n'est pas valide.", 36245000004);
            }
            if (!filter_var($this->mail_from, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email de l'expéditeur (".$this->mail_from.") n'est pas valide.", 36245000005);
            }
            $passage_ligne = "\n";
            // Mary <mary@example.com>
            if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->mail_to)) { // On filtre les serveurs qui rencontrent des bogues.
                $passage_ligne = "\r\n";
            }
            if(empty($this->messageText)) {
                $this->messageText = strip_tags(str_replace("<br />", "\n", $this->messageHTML));
            }
            //Creation de la boundary
            $boundary = "-----=" . md5(rand());
            //Creation du header de l'e-mail.
            $header = "From: " . $mail_from_def . $passage_ligne;
            $header .= "MIME-Version: 1.0" . $passage_ligne;
            $header .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;
            //Creation du message.
            $message = $passage_ligne . "--" . $boundary . $passage_ligne;
            //Ajout du message au format texte.
            $message .= "Content-Type: text/plain; charset=\"".$this->charset."\"" . $passage_ligne;
            $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
            $message .= $passage_ligne . $this->messageText . $passage_ligne;
            //Separateur html et text
            $message .= $passage_ligne . "--" . $boundary . $passage_ligne;
            //Ajout du message au format HTML
            $message .= "Content-Type: text/html; charset=\"".$this->charset."\"" . $passage_ligne;
            $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
            $message .= $passage_ligne . $this->messageHTML . $passage_ligne;
            //Ajout de piece jointe
            foreach ($this->attachments as $file_name) {
                if (file_exists($file_name)) {
                    $file_type = filetype($file_name);
                    $file_size = filesize($file_name);
                    
                    if(($handle = fopen($file_name, "r"))!==false) {
                        $content = fread($handle, $file_size);
                        $content = chunk_split(base64_encode($content));
                        fclose($handle);
                    
                        $message .= $passage_ligne .'--'.$boundary.$passage_ligne;
                        $message .= 'Content-type:'.$file_type.';name='.basename($file_name).$passage_ligne;
                        $message .= 'Content-transfer-encoding:base64'.$passage_ligne;
                        $message .= $passage_ligne . $content.$passage_ligne;
                    } else {
                        throw new Error("Il n'est pas possible d'ouvrir le fichier (".$file_name.").", 36245000006);
                    }
                }
            }
            //Fin du message
            $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
            //Envoi de l'e-mail.
            mail($mail_to_def, html_entity_decode($this->objet), html_entity_decode($message), $header);
            return $this;
        }
        
}