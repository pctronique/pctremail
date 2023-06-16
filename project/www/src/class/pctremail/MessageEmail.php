<?php
/**
 * Pour lire les informations de l'entreprise.
 * numero d'error de la classe '1003XXXXXX'
 */

if (!class_exists('MessageEmail')) {

    /**
     * Creation de la class pour la recuperation des informations de l'entreprise
     */
    class MessageEmail {
        
        private string|null $selectVar;

        private string $object;
        private string $message;
        private string $lien_message;
        private string $lien_modif_pass;
        private string $lien_create_pass;

        /**
         * Le contenu du fichier ini
         *
         * @var array du contenu du fichier ini
         */
        private array $arrayIni;

        private int $nmError;
        private bool $is_error;

        private string|null $dureeValidite;
        private string|null $code;
        private string|null $valide_code;
        private array|null $vars;
        private array|null $varsLien;


        /**
         * le constructeur par defaut
         */
        public function __construct(string|null $file_message = null) {
            $this->selectVar = "{{%s}}";
            $this->nmError = 0;
            $this->is_error = false;
            $this->errorFile = new Error_Log();
            $this->dureeValidite = "";
            $this->object = "";
            $this->message = "";
            $this->code = "";
            $this->valide_code = "";
            $this->lien_message = "";
            $this->lien_modif_pass = "";
            $this->lien_create_pass = "";
            $this->config = new Config();
            $this->vars = [];
            $this->varsLien = [];
            if(empty($file_message)) {
                $file_message = $this->config->getFile_messages();
            }
            if(!empty($file_message) && !empty(trim($file_message))) {
                $file_message = trim($file_message);
                if(!file_exists($file_message)) {
                    $file_message .= ".example";
                }
                if(!file_exists($file_message)) {
                    try {
                        throw new Exception("Impossible de trouver le fichier.\n");
                    } catch (Exception $e) {
                        $this->is_error = true;
                        $this->nmError = 2003000001;
                        $error_message = $e;
                        $this->errorFile->addError($error_message, $this->nmError, $file_message);
                    }
                } else {
                    try {
                        $this->arrayIni = parse_ini_file($file_message, true);
                    } catch (Exception $e) {
                        $this->is_error = true;
                        $this->nmError = 2003000002;
                        $error_message = $e;
                        $this->errorFile->addError($error_message, $this->nmError);
                    }
                }
            } else {
                try {
                    throw new Exception("Il n'y a pas de nom de fichier dans la configuration (pour les messages)\n");
                } catch (Exception $e) {
                    $this->is_error = true;
                    $this->nmError = 2003000000;
                    $error_message = $e;
                    $this->errorFile->addError($error_message, $this->nmError);
                }
            }
            $this->setLien_message($this->config->getLien_message());
            $this->setLien_modif_pass($this->config->getLien_modif_pass());
        }

        /**
         * Set the value of selectVar
         */
        public function setSelectVar(string|null $selectVar): self
        {
            if(!empty($selectVar)) {
                $this->selectVar = $selectVar;
            }
            return $this;
        }

        public function addVar(string|null $name, string|null $value):self {
            if(!empty($name) && !empty($value)) {
                $this->vars[$name] = $value;
            }
            return $this;
        }

        public function addVarLien(string|null $name, string|null $value):self {
            if(!empty($name) && !empty($value)) {
                $this->varsLien[$name] = $value;
            }
            return $this;
        }

        /**
         * recuperer le message.
         */
        public function recupeMessage(string|null $title):self {
            $this->object = "";
            $this->message = "";
            $this->code = "";
            $this->valide_code = "";
            $this->lien_message = "";
            $this->lien_modif_pass = "";
            $this->lien_create_pass = "";
            $this->setLien_message($this->config->getLien_message());
            $this->setLien_modif_pass($this->config->getLien_modif_pass());
            if(!empty($this->arrayIni) && array_key_exists($title, $this->arrayIni)) {
                if(array_key_exists("object", $this->arrayIni[$title]) && array_key_exists("message", $this->arrayIni[$title])) {
                    $this->object = $this->arrayIni[$title]["object"];
                    $this->message = $this->arrayIni[$title]["message"];
                }
            }
            return $this;
        }

        /**
         * le numero d'erreur
         */ 
        public function getNmError():int
        {
                return $this->nmError;
        }

        /**
         * vifier l'existance d'une erreur
         */ 
        public function getIs_error():bool
        {
                return $this->is_error;
        }


        /**
         * Set the value of dureeValidite
         */
        public function setDureeValidite(string|null $dureeValidite): self
        {
            if(!empty($dureeValidite)) {
                $this->dureeValidite = $dureeValidite;
            } else {
                $this->dureeValidite = "";
            }
            return $this;
        }

        /**
         * ajouter un code
         *
         * @return  self
         */ 
        public function setCode(string|null $code):self
        {
            if(!empty($code)) {
                $this->code = $code;
            } else {
                $this->code = "";
            }
            return $this;
        }

        /**
         * ajouter un code de validation
         *
         * @return  self
         */ 
        public function setValide_code(string|null $valide_code):self
        {
            if(!empty($valide_code)) {
                $this->valide_code = $valide_code;
            } else {
                $this->valide_code = "";
            }
            return $this;
        }

        /**
         * modifier le lien pour envoyer un message.
         *
         * @return  self
         */ 
        public function setLien_message(string|null $lien_message):self
        {
            if(!empty($lien_message)) {
                $this->lien_message = $lien_message;
            } else {
                $this->lien_message = "";
            }
            return $this;
        }

        /**
         * modifier le lien pour modifier le mot de passe.
         *
         * @return  self
         */ 
        public function setLien_modif_pass(string|null $lien_modif_pass):self
        {
            if(!empty($lien_modif_pass)) {
                $this->lien_modif_pass = $lien_modif_pass;
            } else {
                $this->lien_modif_pass = "";
            }
            return $this;
        }

        /**
         * modifier le lien de la creation du mot de passe.
         *
         * @return  self
         */ 
        public function setLien_create_pass(string|null $lien_create_pass):self
        {
            if(!empty($lien_create_pass)) {
                $this->lien_create_pass = $lien_create_pass;
            } else {
                $this->lien_create_pass = "";
            }
            return $this;
        }

        private function modifLien(string|null $lien):string|null {
            return str_replace(":/", "://", str_replace("//", "/", str_replace("/./", "/", $this->config->lien_page()."/".$lien)));
        }

        /**
         * remplacer les variables dans les messages.
         */
        private function modifText(string|null $text):string|null {
            if(empty($text)) {
                return "";
            }
            $lien_msg = str_replace("/./", "/", $this->config->lien_page().$this->lien_message);
            $lien_modif_pass = str_replace("/./", "/", $this->config->lien_page().$this->lien_modif_pass);
            $lien_create_pass = str_replace("/./", "/", $this->config->lien_page().$this->lien_create_pass);
            $text = str_replace("{{TITLE_SITE}}", $this->config->getTitleSite(), $text);
            $text = str_replace(strtolower("{{TITLE_SITE}}"), $this->config->getTitleSite(), $text);
            $text = str_replace("{{NAME_SITE}}", $this->config->name_site(), $text);
            $text = str_replace(strtolower("{{NAME_SITE}}"), $this->config->name_site(), $text);
            $text = str_replace("{{LIEN_SITE}}", $this->config->lien_page(), $text);
            $text = str_replace(strtolower("{{LIEN_SITE}}"), $this->config->lien_page(), $text);
            $text = str_replace("{{LIEN_MSG}}", $lien_msg, $text);
            $text = str_replace(strtolower("{{LIEN_MSG}}"), $lien_msg, $text);
            $text = str_replace("{{LIEN_MODIF_PASS}}", $lien_modif_pass, $text);
            $text = str_replace(strtolower("{{LIEN_MODIF_PASS}}"), $lien_modif_pass, $text);
            $text = str_replace("{{LIEN_CREATE_PASS}}", $lien_create_pass, $text);
            $text = str_replace(strtolower("{{LIEN_CREATE_PASS}}"), $lien_create_pass, $text);
            $text = str_replace("{{DATE_VALIDE}}", $this->valide_code, $text);
            $text = str_replace(strtolower("{{DATE_VALIDE}}"), $this->valide_code, $text);
            $text = str_replace("{{CODE}}", $this->code, $text);
            $text = str_replace(strtolower("{{CODE}}"), $this->code, $text);
            $text = str_replace("{{DUREE_VALIDE}}", $this->dureeValidite, $text);
            $text = str_replace(strtolower("{{DUREE_VALIDE}}"), $this->dureeValidite, $text);
            foreach ($this->vars as $key => $value) {
                $text = str_replace(strtoupper("{{".$key."}}"), $value, $text);
            }
            foreach ($this->varsLien as $key => $value) {
                $text = str_replace(strtoupper("{{".$key."}}"), $this->modifLien($value), $text);
            }
            return $text;
        }

        /**
         * recuperer le message.
         */ 
        public function getMessage():string|null
        {
                return $this->modifText($this->message);
        }

        /**
         * recuperer l'objet du message
         */ 
        public function getObject():string|null
        {
                return $this->modifText($this->object);
        }

    }
}