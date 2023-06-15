<?php
/**
 * Pour se connecter a la base de donner a partir du fichier "sgbd_config.php".
 * Pouvoir avoir une connexion a la base de donnees differentes.
 * numero d'error de la classe '36245XXXXXX'
 */

// verifier qu'on n'a pas deja creer la fonction
if (!class_exists('EmailSend')) {

    // fonction pour faire la connexion a la base de donnes
    class EmailSend {

        private string|null $mail_to;
        private string|null $name_to;
        private string|null $mail_from;
        private string|null $name_from;
        private string|null $charset;
        private string|null $objet;
        private string|null $messageHTML;
        private string|null $messageText;
        private array|null $attachments;

        /**
         * Undocumented function
         *
         * @param integer $time timestamp Unix
         */
        public function __construct() {
            $this->mail_to = "";
            $this->mail_from = "";
            $this->charset = "UTF-8";
            $this->objet = "";
            $this->messageHTML = "";
            $this->messageText = "";
            $this->attachments = [];
        }

        /**
         * Set the value of mail
         */
        public function setMailTo(string|null $mail_to, string|null $name_to = null): self
        {
            $this->mail_to = !empty($mail_to) ? $mail_to : "";
            $this->name_to = !empty($name_to) ? $name_to : "";
            if (!filter_var($mail_to, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".(!empty($mail_to) ? $mail_to : (isset($mail_to) ? $mail_to : "NULL")).") n'est pas valide.", 36245000001);
            }
            return $this;
        }

        /**
         * Set the value of mail_from
         */
        public function setMailFrom(string|null $mail_from, string|null $name_from = null): self
        {
            $this->mail_from = !empty($mail_from) ? $mail_from : "";
            $this->name_from = !empty($name_from) ? $name_from : "";
            if (!filter_var($mail_from, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".(!empty($mail_from) ? $mail_from : (isset($mail_from) ? $mail_from : "NULL")).") n'est pas valide.", 36245000002);
            }
            return $this;
        }

        private function encodingsChar(string|null $charset): string|null {
            foreach (mb_list_encodings() as $value) {
                if(strtolower($charset) == strtolower($value)) {
                    return $value;
                }
            }
            return null;
        }

        /**
         * Set the value of charset
         */
        public function setCharset(string|null $charset): self
        {
            if(!empty($charset)) {
                if(!empty($encoding = $this->encodingsChar($charset))) {
                    $this->charset = $encoding;
                } else {
                    throw new Exception("Le charset (".(!empty($charset) ? $charset : (isset($charset) ? $charset : "NULL")).") n'est pas valide.", 36245000009);
                }
            } else {
                $this->charset = "";
            }
            return $this;
        }

        /**
         * Set the value of objet
         */
        public function setObjet(string|null $objet): self
        {
            $this->objet = !empty($objet) ? $objet : "";
            return $this;
        }

        /**
         * 
         */
        private function textAndFile(string|null $message): string|null {
            if (file_exists($message)) {
                if(($mime = mime_content_type($message))!==false && strtolower($mime) == "text/plain") {
                    if(($content = file_get_contents($message))!==false) {
                        return !empty($content) ? $content : "";
                    } else {
                        throw new Error("Il n'est pas possible d'ouvrir le fichier (".(!empty($message) ? $message : (isset($message) ? $message : "NULL")).").", 36245000007);
                        return "";
                    }
                }else {
                    throw new Error("Le fichier n'est pas valide(".(!empty($message) ? $message : (isset($message) ? $message : "NULL")).").", 36245000008);
                    return "";
                }
            } else {
                return !empty($message) ? $message : "";
            }
        }

        /**
         * Set the value of messageHTML
         */
        public function setMessageHTML(string|null $messageHTML): self
        {
            $this->messageHTML = $this->textAndFile($messageHTML);
            return $this;
        }

        /**
         * Set the value of messageText
         */
        public function setMessageText(string|null $messageText): self
        {
            $this->messageText = $this->textAndFile($messageText);
            return $this;
        }

        /**
         * Set the value of attachment
         */
        public function addAttachment(string|null $file): self
        {
            if(empty($file)) {
                return $this;
            }
            if(!is_file($file)) {
                throw new Error("Le fichier (".(!empty($file) ? $file : (isset($file) ? $file : "NULL")).") n'est pas valide.", 36245000003);
            }
            array_push($this->attachments, $file);
            return $this;
        }

        /**
         * Pour envoyer un message html (si le message ne peut pas etre lut en html, il sera affiche en texte).
         * Si le message texte est vide, il sera remplacer par le htlm (sans les balises).
         */
        public function send():self {
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
            if(empty($this->messageText) && !empty($this->messageHTML)) {
                $this->messageText = strip_tags(str_replace("<br />", "\n", $this->messageHTML));
            } else if(!empty($this->messageText) && empty($this->messageHTML)){
                $this->messageHTML = str_replace("\n", "<br />\n", strip_tags($this->messageText));
                $this->messageText = strip_tags($this->messageText);
            } else if(!empty($this->messageText) && !empty($this->messageHTML)){
                $this->messageText = strip_tags($this->messageText);
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
                        $message .= 'Content-Type: '.$file_type.'; name='.basename($file_name).$passage_ligne;
                        $message .= 'Content-Transfer-Encoding: base64'.$passage_ligne;
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
}
