<?php
include_once "SpaceWeb/SpaceWebAPI.php";

$Spider = new SpaceWebAPI("grizzly","YcWmRbrXT","1.114.20220208150755");
$Spider->addRandomDomain ($Spider->getToken ());
if ($Spider){
    echo "Домен был создан";
}else{
    echo "Создание не удалось, произошла ошибка";
}
