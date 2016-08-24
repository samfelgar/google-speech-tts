<?php

namespace GoogleSpeech;

use GuzzleHttp\Client;


class TextToSpeech
{
    private $client;
    private $file;
    private $lang;

    public function __construct()
    {
        $this->client = new Client();
        $this->file = new File();
    }
    
    public function download($text){

        if(!$this->lang){
            throw new \Exception("Language doesn't informed");
        }
        if(!$this->file){
            throw new \Exception("Path doesn't informed");
        }

        if(!$text){
            throw new \Exception("Text doesn't informed");
        }

        $path = $this->file->getCompletePath();
        $pathInfo = pathinfo($path);
        if (!is_writable($pathInfo['dirname'])) {
            chmod($pathInfo['dirname'], 0755);
        }

        $url = $this->mountUrl($text);
        $response = $this->client->get($url,[
            'headers' => [
                'Referer' => 'http://translate.google.com/',
                'User-Agent'=> 'stagefright/1.2 (Linux;Android 5.0)',
                'Content-type' => 'audio/mpeg'
            ],
            'save_to' => $path
        ]);

        if($response->getStatusCode() == 200){
            return true;
        }

        throw new \Exception('Something bad');

    }

    public function withLanguage($lang){
        $this->lang = $lang;
        return $this;
    }

    public function inPath($path){
        $this->file->setPath($path);
        return $this;
    }

    public function withName($name){
        $this->file->setName($name);
        return $this;
    }

    private function mountUrl($text){

        $qParams = [
            'ie' => 'UTF-8',
            'q' => $text,
            'client' => 'tw-ob',
            'tl' => $this->lang
        ];

        return GOOGLE_TEXT_TO_SPEECH_URL . http_build_query($qParams);
    }

}