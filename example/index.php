<?php
require_once "../bootstrap.php";

use AlboVieira\GoogleSpeech\TextToSpeech;

$speech = new TextToSpeech();
$speech
    ->withLanguage('en-us')
    ->inPath('../audios');

for($i=0;$i<10;$i++){
    $speech->withName('output' . $i);
    $speech->download('I would try something like that ' . $i);
    
    echo 'File generated:' . $speech->getCompletePath() . '<br>';
}