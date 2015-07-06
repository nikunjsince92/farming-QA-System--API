<?php
require_once('config.inc.php');
require_once('class/MicrosoftTranslator.class.php');
//ACCOUNT_KEY you have to get from https://datamarket.azure.com/dataset/1899a118-d202-492c-aa16-ba21c33c06cb
$translator = new MicrosoftTranslator("ZWXY16Y45VGP/tLUWwkikzop8iuzPBBcqzhtqpO4+zU");

$text_to_translate = 'Hello World'; 
$from = 'en';
$to = 'es';
$translator->translate($from, $to, $text_to_translate);
print_r($translator);
$data = json_decode($translator->response->jsonResponse, true);
echo $data["translation"];
?>