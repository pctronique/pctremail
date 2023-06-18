<?php
// verifier qu'on n'a pas deja creer la classe
if (!class_exists('MessageEmail')) {

    /**
     * Récupération d'un message et le modifier avant de l'envoyer par e-mail.
     * (Numéro d'error de la classe '63736XXXXXX')
     * 
     * @version 1.1.1.0
     * @author NAULOT ludovic <dev@pctronique.fr>
     */
    class MessageEmail {
        
        private string|null $selectVar;
        private string|null $object;
        private string|null $message;
        private array|null $arrContent;
        private array|null $vars;
        private bool $isFileIni;
        private bool $isFileJson;
        
        /**
         * le constructeur.
         * 
         * @param string|null $file_message
         * @return string
         * @throws Error
         */
        public function __construct(string|null $file_message = null) {
            $this->selectVar = "{{%s}}";
            $this->object = "";
            $this->message = "";
            $this->isFileIni = false;
            $this->isFileJson = false;
            $this->arrContent = [];
            $this->vars = [];
            if(!empty($file_message) && is_file($file_message)) {
                if(($mime = mime_content_type($file_message))!==false && (strtolower($mime) == "text/plain" || strtolower($mime) == "application/json")) {
                    if(($content = file_get_contents($file_message))!==false) {
                        if(strtolower($mime) == "application/json") {
                            try {
                                $contentJson = json_decode($content, true);
                                $this->arrContent = $contentJson;
                                $this->isFileJson = !empty($this->arrContent);
                            } catch (Throwable $e) {
                                $this->isFileJson = false;
                            }
                        } else {
                            try {
                                $contentIni = parse_ini_string($content, true);
                                $this->arrContent = $contentIni;
                                $this->isFileIni = !empty($this->arrContent);
                            } catch (Throwable $e) {
                                $this->isFileIni = false;
                            }
                        }
                        if(!($this->isFileIni || $this->isFileJson)) {
                            throw new Error("Il n'est pas possible de lire le fichier (".(!empty($file_message) ? $file_message : (isset($file_message) ? $file_message : "NULL")).").", 63736000002);
                        }
                    } else {
                        throw new Error("Il n'est pas possible d'ouvrir le fichier (".(!empty($file_message) ? $file_message : (isset($file_message) ? $file_message : "NULL")).").", 63736000001);
                    }
                }else {
                    throw new Error("Le fichier n'est pas valide(".(!empty($file_message) ? $file_message : (isset($file_message) ? $file_message : "NULL")).").", 36245000006);
                    return "";
                }
            }
        }

        /**
         * 
         * @param string|null $selectVar
         * @return self
         * @throws Error
         */
        public function setSelectVar(string|null $selectVar): self
        {
            if (!str_contains($selectVar, '%s')) {
                throw new Error("Le contenu de la sélection de variable n'est pas valide, il manque %s (".$selectVar.")", 63736000003);
                return $this;
            }
            if(!empty($selectVar)) {
                $this->selectVar = $selectVar;
            }
            return $this;
        }

        /**
         * 
         * @param string|null $name
         * @param string|null $value
         * @return self
         */
        public function addVar(string|null $name, string|null $value):self {
            if(!empty($name) && !empty($value)) {
                $this->vars[$name] = $value;
            }
            return $this;
        }

        /**
         * 
         * @param string|null $title
         * @return self
         */
        public function recupeMessage(string|null $title):self {
            $this->object = "";
            $this->message = "";
            if(($this->isFileIni || $this->isFileJson) && !empty($this->arrContent) && array_key_exists($title, $this->arrContent)) {
                if(array_key_exists("object", $this->arrContent[$title]) && array_key_exists("message", $this->arrContent[$title])) {
                    $this->object = $this->modifText($this->arrContent[$title]["object"]);
                    $this->message = $this->modifText($this->arrContent[$title]["message"]);
                }
            }
            return $this;
        }

        /**
         * 
         * @param string|null $text
         * @return string|null
         */
        private function modifText(string|null $text):string|null {
            if(empty($text)) {
                return "";
            }
            foreach ($this->vars as $key => $value) {
                $text = str_replace(strtoupper(str_replace("%s", $key, $this->selectVar)), $value, $text);
                $text = str_replace(strtolower(str_replace("%s", $key, $this->selectVar)), $value, $text);
            }
            return $text;
        }

        /**
         * 
         * @return string|null
         */
        public function getMessage():string|null
        {
                return $this->modifText($this->message);
        }

        /**
         * 
         * @return string|null
         */
        public function getObject():string|null
        {
                return $this->modifText($this->object);
        }

        /**
         * 
         * @param string|null $object
         * @return string|null
         */
        public function object(string|null $object):string|null
        {
            if(empty($object)) {
                return "";
            }
            return $this->modifText($object);
        }

        /**
         * 
         * @param string|null $message
         * @return string|null
         * @throws Error
         */
        public function message(string|null $message):string|null
        {
            if(empty($message)) {
                return "";
            }
            if(!empty($message) && is_file($message)) {
                if(($mime = mime_content_type($message))!==false && strtolower($mime) == "text/plain") {
                    if(($content = file_get_contents($message))!==false) {
                        $message = $content;
                    } else {
                        throw new Error("Il n'est pas possible d'ouvrir le fichier (".(!empty($message) ? $message : (isset($message) ? $message : "NULL")).").", 63736000004);
                    }
                }else {
                    throw new Error("Le fichier n'est pas valide(".(!empty($message) ? $message : (isset($message) ? $message : "NULL")).").", 63736000005);
                    return "";
                }
            }
            return $this->modifText($message);
        }

    }
}
