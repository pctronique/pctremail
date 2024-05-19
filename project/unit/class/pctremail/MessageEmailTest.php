<?php
use PHPUnit\Framework\TestCase;

define("RACINE_UNIT", dirname(__FILE__)."/../..");
require_once(RACINE_UNIT . '/config_path.php');
require_once(RACINE_UNIT . '/function_test.php');
require_once(UNIT_RACINE_WWW . '/src/class/pctremail/MessageEmail.php');

/**
 * ClassNameTest
 * @group group
 */
class MessageEmailTest extends TestCase
{
    /**
     * @var Create_folder
     */
    protected MessageEmail|null $object;
    private string|null $folderSave;
    private string|null $fileValide;
    private string|null $fileError;
    private string|null $fileVerif;
    private string|null $nameFileValide;
    private string|null $namefileError;
    private string|null $namefileVerif;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new MessageEmail();
        $this->folderSave = __DIR__ . "/../../upload/";
        if (!is_dir($this->folderSave)) {
            mkdir($this->folderSave, 0777, true);
        }
        $this->nameFileValide = "EmailMsgValide.txt";
        $this->namefileError = "EmailMsgError.txt";
        $this->namefileVerif = "EmailMsgVerif.txt";
        $this->fileValide = $this->folderSave.$this->nameFileValide;
        $this->fileError = $this->folderSave.$this->namefileError;
        $this->fileVerif = $this->folderSave.$this->namefileVerif;
    }

    private function deleteFile(): self
    {
        if (is_file($this->fileValide)) {
            unlink($this->fileValide);
        }
        if (is_file($this->fileError)) {
            unlink($this->fileError);
        }
        if (is_file($this->fileVerif)) {
            unlink($this->fileVerif);
        }
        return $this;
    }

    private function displayValidated(array|null $tabVal, string|null $name = null, bool $verif = false): self
    {
        $name = !empty($name) ? $name : "DATA";
        $tabVal = array_unique($tabVal);
        $content = "------------------------------" . "\n\n";
        $content .= "-- VALIDATED : " . $name . "\n";
        $content .= "------------------------------" . "\n";
        foreach ($tabVal as $value) {
            //echo $value."\n";
            $content .= (!empty($value) ? $value : (isset($value) ? $value : "NULL")) . "\n";
        }
        file_put_contents($this->fileValide, $content, FILE_APPEND);
        if ($verif) {
            file_put_contents($this->fileVerif, $content, FILE_APPEND);
        }
        return $this;
    }

    private function displayError(array|null $tabError, string|null $name = null, bool $verif = false): self
    {
        $name = !empty($name) ? $name : "DATA";
        $tabError = array_unique($tabError);
        $content = "------------------------------" . "\n\n";
        $content .= "-- ERROR : " . $name . "\n";
        $content .= "------------------------------" . "\n";
        foreach ($tabError as $value) {
            //echo $value."\n";
            $content .= (!empty($value) ? $value : (isset($value) ? $value : "NULL")) . "\n";
        }
        file_put_contents($this->fileError, $content, FILE_APPEND);
        if ($verif) {
            file_put_contents($this->fileVerif, $content, FILE_APPEND);
        }
        return $this;
    }

    public function testObj(): self
    {
        $this->deleteFile();
        $tabError = [];
        $tabVal = [];
        foreach (array_string_all() as $value) {
            try {
                $this->object = new MessageEmail($value);
                $this->testGetMessage();
                $this->testGetObject();
                $this->testGetKeys();
                array_push($tabVal, 'Valeur valide : ' . $value);
            } catch (Throwable $th) {
                array_push($tabError, 'Problème : ' . $th->getMessage());
            }
        }
        $this->displayValidated($tabVal, "MessageEmail");
        $this->displayError($tabError, "MessageEmail", true);
        $this->assertNotNull($this->object);
        return $this;
    }

    public function testsetFormatVar(): self
    {
        $tabError = [];
        $tabVal = [];
        foreach (array_string_all() as $value) {
            try {
                $this->object->setFormatVar($value);
                $this->testGetMessage();
                $this->testGetObject();
                $this->testGetKeys();
                array_push($tabVal, 'Valeur valide : ' . $value);
            } catch (Throwable $th) {
                array_push($tabError, 'Problème : ' . $th->getMessage());
            }
        }
        $this->displayValidated($tabVal, "setFormatVar", true);
        $this->displayError($tabError, "setFormatVar");
        $this->assertNotNull($this->object);
        return $this;
    }

    public function testAddVar(): self
    {
        foreach (array_string_all() as $value) {
            foreach (array_string_all() as $value2) {
                $this->object->addVar($value, $value2);
                $this->testGetMessage();
                $this->testGetObject();
                $this->testGetKeys();
            }
        }
        $this->assertNotNull($this->object);
        return $this;
    }

    /**
     * recuperer le message.
     */
    public function testRecupeMessage(): self
    {
        foreach (array_string_all() as $value) {
            $this->object->recupeMessage($value);
            $this->testGetMessage();
            $this->testGetObject();
            $this->testGetKeys();
        }
        $this->assertNotNull($this->object);
        return $this;
    }

    /**
     * recuperer le message.
     */
    public function testGetMessage(): self
    {
        $testFunction = $this->object->getMessage();
        $this->assertNotNull($testFunction);
        $this->assertIsString($testFunction);
        return $this;
    }

    /**
     * recuperer l'objet du message
     */
    public function testGetObject(): self
    {
        $testFunction = $this->object->getObject();
        $this->assertNotNull($testFunction);
        $this->assertIsString($testFunction);
        return $this;
    }

    /**
     * recuperer l'objet du message
     */
    public function testObject(): self
    {
        foreach (array_string_all() as $value) {
            $testFunction = $this->object->object($value);
            $this->assertNotNull($testFunction);
            $this->assertIsString($testFunction);
        }
        return $this;
    }

    /**
     * recuperer l'objet du message
     */
    public function testMessage(): self
    {
        $tabError = [];
        $tabVal = [];
        foreach (array_string_all() as $value) {
            try {
                $testFunction = $this->object->message($value);
                $this->assertNotNull($testFunction);
                $this->assertIsString($testFunction);
                array_push($tabVal, 'Valeur valide : ' . $value);
            } catch (Throwable $th) {
                array_push($tabError, 'Problème : ' . $th->getMessage());
            }
        }
        $this->displayValidated($tabVal, "Message");
        $this->displayError($tabError, "Message", true);
        return $this;
    }

    public function testGetKeys(): self
    {
        $testFunction = $this->object->getKeys();
        $this->assertNotNull($testFunction);
        $this->assertIsArray($testFunction);
        return $this;
    }
        
    public function testFinal():self {
        $folderTest = __DIR__."/../../validtest/";
        $txtFileValide = file_get_contents($this->folderSave.$this->nameFileValide);
        $txtFileError = file_get_contents($this->folderSave.$this->nameFileError);
        $txtFileVerif = file_get_contents($this->folderSave.$this->nameFileVerif);
        $txtTstFileValide = file_get_contents($folderTest.$this->nameFileValide);
        $txtTstFileError = file_get_contents($folderTest.$this->nameFileError);
        $txtTstFileVerif = file_get_contents($folderTest.$this->nameFileVerif);
        $this->assertEquals($txtFileValide, $txtTstFileValide);
        $this->assertEquals($txtFileError, $txtTstFileError);
        $this->assertEquals($txtFileVerif, $txtTstFileVerif);
        return $this;
    }

}
