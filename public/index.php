<?php
require_once "../bootstrap.php";
require_once "../config.php";

use GoogleSpeech\TextToSpeech;

$speech = new TextToSpeech();
$speech
    ->withLanguage('en-us')
    ->inPath('../audios');

for($i=0;$i<10;$i++){
    $speech->withName('audio' . $i);
    $speech->download('testing ' . $i);
}