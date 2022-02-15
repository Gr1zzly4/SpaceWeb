<?php

include_once "SpaceWeb/SpaceWebAPI.php";
use SpaceWeb\SpaceWebAPI;

include_once "../env.php";
echo $_SERVER['DOCUMENT_ROOT'];

$LOGIN = $_ENV["LOGIN"];
$PASSWORD = $_ENV["PASSWORD"];

$Spider = new SpaceWebAPI("$LOGIN","$PASSWORD","1.114.20220208150755");
$result = $Spider->addRandomDomain ($Spider->getToken ());
if ($result){
    echo "Домен был создан";
}else{
    echo "Создание не удалось, произошла ошибка";
}
