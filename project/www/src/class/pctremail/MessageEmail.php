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
        private array|null $keys;
        private bool $isFileIni;
        private bool $isFileJson;
        
        /**
         * le constructeur.
         * Il est possible d'entrer un fichier ini ou json qui contient les messages.
         * 
         * @param string|null $file_message inclure un fichier (ini ou json)
         * @return string
         * @throws Error en cas d'erreur de fichier
         */
        public function __construct(string|null $file_message = null) {
            // initialisation des variables
            $this->keys = [];
            $this->selectVar = "{{%s}}";
            $this->object = "";
            $this->message = "";
            $this->isFileIni = false;
            $this->isFileJson = false;
            $this->arrContent = [];
            $this->vars = [];
            // vérifier que c'est bien un fichier
            if(!empty($file_message) && is_file($file_message)) {
                // vérifier le contenu du fichier qu'il soit valide
                if(($mime = mime_content_type($file_message))!==false && (strtolower($mime) == "text/plain" || strtolower($mime) == "application/json")) {
                    // récupérer le contenu du fichier
                    if(($content = file_get_contents($file_message))!==false) {
                        // vérifier que le fichier est json
                        if(strtolower($mime) == "application/json") {
                            try {
                                $contentJson = json_decode($content, true);
                                $this->arrContent = $contentJson;
                                $this->isFileJson = !empty($this->arrContent);
                            } catch (Throwable $e) {
                                $this->isFileJson = false;
                            }
                        } else {
                            // vérifier que le fichier est ini
                            try {
                                $contentIni = parse_ini_string($content, true);
                                $this->arrContent = $contentIni;
                                $this->isFileIni = !empty($this->arrContent);
                            } catch (Throwable $e) {
                                $this->isFileIni = false;
                            }
                        }
                        // si c'est aucun des deux fichiers
                        if(!($this->isFileIni || $this->isFileJson)) {
                            throw new Error("Il n'est pas possible de lire le fichier (".(!empty($file_message) ? $file_message : (isset($file_message) ? $file_message : "NULL")).").", 63736000002);
                        } else {
                            $this->keys = array_keys($this->arrContent);
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
         * Pour pouvoir sélectionner une variable dans le message et doit contenir %s.
         * exemple : {{%s}}
         * 
         * @param string|null $selectVar la sélection de variable doit contenir %s.
         * @return self
         * @throws Error si le nom ne contient pas %s
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
         * Ajouter une variable
         * 
         * @param string|null $name le nom de la variable
         * @param string|null $value entrer une valeur à la variable
         * @return self
         */
        public function addVar(string|null $name, string|null $value):self {
            if(!empty($name) && !empty($value)) {
                $this->vars[$name] = $value;
            }
            return $this;
        }

        /**
         * récupérer un message et l'objet dans le fichier ini ou json à partir d'un nom de clé
         * 
         * @param string|null $key nom clé du message
         * @return self
         */
        public function recupeMessage(string|null $key):self {
            $this->object = "";
            $this->message = "";
            // vérifier qu'on a bien ouvert un fichier valide
            if(($this->isFileIni || $this->isFileJson) && !empty($this->arrContent) && array_key_exists($key, $this->arrContent)) {
                // récupérer le message
                if(array_key_exists("object", $this->arrContent[$key]) && array_key_exists("message", $this->arrContent[$key])) {
                    $this->object = $this->modifText($this->arrContent[$key]["object"]);
                    $this->message = $this->modifText($this->arrContent[$key]["message"]);
                }
            }
            return $this;
        }

        /**
         * Remplacer les variables dans le texte
         * 
         * @param string|null $text texte avec variable
         * @return string|null texte avec les variables remplacés
         */
        private function modifText(string|null $text):string|null {
            if(empty($text)) {
                return "";
            }
            // remplacer les variables
            foreach ($this->vars as $key => $value) {
                $text = str_replace(strtoupper(str_replace("%s", $key, $this->selectVar)), $value, $text);
                $text = str_replace(strtolower(str_replace("%s", $key, $this->selectVar)), $value, $text);
            }
            return $text;
        }

        /**
         * Récupérer le message du fichier aprés utilisation de la méthode recupeMessage.
         * 
         * @return string|null récupérer le message.
         */
        public function getMessage():string|null
        {
                return $this->modifText($this->message);
        }

        /**
         * Récupérer l'objet du fichier aprés utilisation de la méthode recupeMessage.
         * 
         * @return string|null récupérer l'objet
         */
        public function getObject():string|null
        {
                return $this->modifText($this->object);
        }

        /**
         * Entrer un texte pour l'objet avec des variables à remplacer.
         * 
         * @param string|null $object texte objet avec variable
         * @return string|null texte objet avec variable remplacé
         */
        public function object(string|null $object):string|null
        {
            if(empty($object)) {
                return "";
            }
            // afficher le texte sans les variables
            return $this->modifText($object);
        }

        /**
         * Entrer un texte ou fichier texte pour le message avec des variables à remplacer.
         * 
         * @param string|null $message le message (fichier ou texte) avec des variables à remplacer.
         * @return string|null le message avec des variables remplacés.
         * @throws Error
         */
        public function message(string|null $message):string|null
        {
            if(empty($message)) {
                return "";
            }
            // vérifier si c'est un fichier
            if(!empty($message) && is_file($message)) {
                // vérifier que c'est bien un fichier texte
                if(($mime = mime_content_type($message))!==false && strtolower($mime) == "text/plain") {
                    // récuper le contenu
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
            // afficher le texte sans les variables
            return $this->modifText($message);
        }

        /**
         * Récupère les clés du fichier.
         * 
         * @return array|null les clés du fichier.
         */
        public function getKeys(): array|null
        {
            return $this->keys;
        }

    }
}
