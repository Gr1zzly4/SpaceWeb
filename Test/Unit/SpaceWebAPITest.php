<?php

require_once "src/SpaceWeb/SpaceWebAPI.php";
use PHPUnit\Framework\TestCase;

class SpaceWebAPITest extends TestCase{

    private array $tru_object = array();
    private array $false_object = array();

    private $login = "grizzly";
    private $password = "YcWmRbrXT";
    private $version = "1.114.20220208150755";

    protected function setUp(): void{
        $this->tru_object[] = new SpaceWebAPI($this->login,$this->password,$this->version);
        $this->tru_object[] = new SpaceWebAPI($this->login,$this->password,"");
        $this->false_object[] = new SpaceWebAPI("",$this->password,$this->version);
        $this->false_object[] = new SpaceWebAPI($this->login,"",$this->version);
    }

    public function testgetToken():void{

        foreach ($this->tru_object as $ob){
            $this->assertIsString($ob->getToken());
        }

        foreach ($this->false_object as $ob){
            $this->assertFalse($ob->getToken());
        }
    }

    public function testaddRandomDomain():void{

        foreach ($this->tru_object as $ob){
            $this->assertNotFalse($ob->addRandomDomain ($ob->getToken()));
        }

        foreach ($this->false_object as $ob){
            $this->assertFalse($ob->addRandomDomain ($ob->getToken()));
        }

    }

}
