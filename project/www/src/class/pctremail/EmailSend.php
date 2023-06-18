<?php
// verifier qu'on n'a pas deja creer la classe
if (!class_exists('EmailSend')) {

    /**
     * Pour envoyer des e-mail.
     * (Numéro d'error de la classe '36245XXXXXX')
     * 
     * @version 1.1.1.0
     * @author NAULOT ludovic <dev@pctronique.fr>
     */
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
         * le constructeur par défaut.
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
         * Entrer le destinataire.
         * 
         * @param string|null $mail_to l'e-mail du destinataire
         * @param string|null $name_to le nom du destinataire
         * @return self
         * @throws Error en cas d'erreur sur l'e-mail
         */
        public function setMailTo(string|null $mail_to, string|null $name_to = null): self
        {
            $this->mail_to = !empty($mail_to) ? $mail_to : "";
            $this->name_to = !empty($name_to) ? $name_to : "";
            // vérifier la validité de l'e-mail
            if (!filter_var($mail_to, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".(!empty($mail_to) ? $mail_to : (isset($mail_to) ? $mail_to : "NULL")).") n'est pas valide.", 36245000001);
            }
            return $this;
        }

        /**
         * Entrer l'expéditeur.
         * 
         * @param string|null $mail_from l'e-mail de l'expéditeur
         * @param string|null $name_from le nom de l'expéditeur
         * @return self
         * @throws Error en cas d'erreur sur l'e-mail
         */
        public function setMailFrom(string|null $mail_from, string|null $name_from = null): self
        {
            $this->mail_from = !empty($mail_from) ? $mail_from : "";
            $this->name_from = !empty($name_from) ? $name_from : "";
            // vérifier la validité de l'e-mail
            if (!filter_var($mail_from, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email (".(!empty($mail_from) ? $mail_from : (isset($mail_from) ? $mail_from : "NULL")).") n'est pas valide.", 36245000002);
            }
            return $this;
        }

        /**
         * Récupérer le jeu de caractères du text (exemple : UTF-8).
         * 
         * @param string|null $charset le jeu de caractères (exemple : UTF-8).
         * @return string|null le bon jeu de caractères
         */
        private function encodingsChar(string|null $charset): string|null {
            foreach (mb_list_encodings() as $value) {
                if(strtolower($charset) == strtolower($value)) {
                    return $value;
                }
            }
            return null;
        }

        /**
         * Entrer le jeu de caractères du text (exemple : UTF-8).
         * 
         * @param string|null $charset le jeu de caractères (exemple : UTF-8).
         * @return self
         * @throws Exception si le jeu de caractères n'est pas valide
         */
        public function setCharset(string|null $charset): self
        {
            // vérifier qu'on a bien entré un charset
            if(!empty($charset)) {
                // vérifier la validité de celui-ci
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
         * Entrer l'objet du message
         * 
         * @param string|null $objet l'objet du message
         * @return self
         */
        public function setObjet(string|null $objet): self
        {
            $this->objet = !empty($objet) ? $objet : "";
            return $this;
        }

        /**
         * Récupérer le message texte à partir d'un format texte ou d'un chemin du fichier texte
         * 
         * @param string|null $message le message (texte ou le chemin du fichier texte)
         * @return string|null le texte à afficher
         * @throws Error en cas d'erreur sur le fichier.
         */
        private function textAndFile(string|null $message): string|null {
            // vérifier qu'on a entré un fichier
            if (file_exists($message)) {
                // vérifier que c'est bien un fichier texte
                if(($mime = mime_content_type($message))!==false && strtolower($mime) == "text/plain") {
                    // récupérer le contenu
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
         * Entrer le message sous format texte html ou le chemin du fichier texte html.
         * 
         * @param string|null $messageHTML le message (texte html ou le chemin du fichier texte html)
         * @return self
         * @throws Error en cas d'erreur sur le fichier.
         */
        public function setMessageHTML(string|null $messageHTML): self
        {
            $this->messageHTML = $this->textAndFile($messageHTML);
            return $this;
        }

        /**
         * Entrer le message sous format texte ou le chemin du fichier texte.
         * 
         * @param string|null $messageText le message (texte ou le chemin du fichier texte)
         * @return self
         * @throws Error en cas d'erreur sur le fichier.
         */
        public function setMessageText(string|null $messageText): self
        {
            $this->messageText = $this->textAndFile($messageText);
            return $this;
        }

        /**
         * Ajouter une pièce jointe à l'e-mail
         * @param string|null $file le chemin de la pièce jointe sur le serveur
         * @return self
         * @throws Error en cas de problème avec le fichier
         */
        public function addAttachment(string|null $file): self
        {
            if(empty($file)) {
                return $this;
            }
            // vérifier la validiter du fichier
            if(!is_file($file)) {
                throw new Error("Le fichier (".(!empty($file) ? $file : (isset($file) ? $file : "NULL")).") n'est pas valide.", 36245000003);
            }
            // ajouter le fichier à la liste
            array_push($this->attachments, $file);
            return $this;
        }

        /**
         * Pour envoyer l'e-mail au destinataire.
         * 
         * @return self
         * @throws Error en cas d'erreur lors de l'envoie du message
         */
        public function send():self {
            // récuperer l'email complet du destinataire
            $mail_from_def = "\"" . $this->mail_from . "\" <" . $this->mail_from . ">";
            if(!empty($this->name_from)) {
                $mail_from_def = "\"" . $this->name_from . "\" <" . $this->mail_from . ">";
            }
            // récuperer l'email complet de l'expéditeur
            $mail_to_def = "\"" . $this->mail_to . "\" <" . $this->mail_to . ">";
            if(!empty($this->name_to)) {
                $mail_to_def = "\"" . $this->name_to . "\" <" . $this->mail_to . ">";
            }
            // vérifier la validiter de l'e-mail du destinataire
            if (!filter_var($this->mail_to, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email du destinataire (".$this->mail_to.") n'est pas valide.", 36245000004);
            }
            // vérifier la validiter de l'e-mail de l'expéditeur
            if (!filter_var($this->mail_from, FILTER_VALIDATE_EMAIL)) {
                throw new Error("L'adresse email de l'expéditeur (".$this->mail_from.") n'est pas valide.", 36245000005);
            }
            // création du passage à la ligne.
            $passage_ligne = "\n";
            // On filtre les serveurs qui rencontrent des bogues.
            if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->mail_to)) { 
                $passage_ligne = "\r\n";
            }
            // créer et récupérer les messages html et texte brut
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
