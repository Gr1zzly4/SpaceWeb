<?php
require_once "src/SpaceWeb/ClassAPI.php";

use PHPUnit\Framework\TestCase;
use SpaceWeb\ClassAPI;
include_once __DIR__ . "/env.php";

$LOGIN = $_ENV["LOGIN"];
$PASSWORD = $_ENV["PASSWORD"];

class ClassAPITest extends TestCase{

    /**
     * @throws ReflectionException
     */
    public function testsendRequest(){
        $LOGIN = $_ENV["LOGIN"];
        $PASSWORD = $_ENV["PASSWORD"];
        $class = new ReflectionClass('ClassAPI');
        $method = $class->getMethod('sendRequest');
        $method->setAccessible(true);


        $header = array(
            'Content-type: application/json; charset=utf-8',
            'Accept: application/json'
        );
        $data =  array("jsonrpc"=> "2.0","version"=>"1.114.20220208150755","method"=>"getToken","params"=>array("login"=>$LOGIN,"password"=>$PASSWORD));
        $data = json_encode ($data);
        $url = "https://api.sweb.ru/notAuthorized";
        $method_req = "POST";

        $obj = new ClassAPI();

        /** Right case*/
        $result = $method->invoke($obj, $data, $url, $header, $method_req);
        $this->assertIsArray($result);

        /** Invalid json */
        $result = $method->invoke($obj, null, $url, $header, $method_req);
        $this->assertIsArray($result);

        /** Invalid url*/
        $result = $method->invoke($obj, $data, null, $header, $method_req);
        $this->assertFalse($result);

        /** Invalid header*/
        $result = $method->invoke($obj, $data, $url, null, $method_req);
        $this->assertFalse($result);

        /** Method null*/
        $result = $method->invoke($obj, $data, $url, $header, null);
        $this->assertFalse($result);
    }

}
