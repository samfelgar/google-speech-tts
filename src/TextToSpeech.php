<?php

namespace AlboVieira\GoogleSpeech;

use GuzzleHttp\Client;

/**
 * Class TextToSpeech
 * @package GoogleSpeech
 */
class TextToSpeech
{
    const GOOGLE_TEXT_TO_SPEECH_URL = "http://translate.google.com/translate_tts?";

    private $client;
    private $file;
    private $lang;

    /**
     * TextToSpeech constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->file = new File();
    }

    /**
     * @param $text
     * @return bool
     * @throws \Exception
     */
    public function download($text)
    {
        if (!$this->lang) {
            throw new \Exception("Language doesn't informed");
        }
        if (!$this->file->getPath()) {
            throw new \Exception("Path doesn't informed");
        }
        if (!$text) {
            throw new \Exception("Text doesn't informed");
        }

        $path = $this->file->getPath();
        if (!is_dir($path)) {
            $created = mkdir($path, 0755, true);
            if (!$created) {
                throw new \Exception("Can't create a folder with the path given");
            }
        }

        $url = $this->mountUrl($text);
        $response = $this->client->get($url, [
            'headers' => [
                'Referer' => 'http://translate.google.com/',
                'User-Agent' => 'stagefright/1.2 (Linux;Android 5.0)',
                'Content-type' => 'audio/mpeg'
            ],
            'save_to' => $this->file->getCompletePath()
        ]);

        if ($response->getStatusCode() == 200) {
            return true;
        }

        throw new \Exception('Something bad');
    }

    /**
     * @param $lang
     * @return $this
     */
    public function withLanguage($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function inPath($path)
    {
        $this->file->setPath($path);
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function withName($name)
    {
        $this->file->setName($name);
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCompletePath()
    {
        return $this->file->getCompletePath();
    }

    /**
     * @param $text
     * @return string
     */
    public function mountUrl($text)
    {

        $qParams = [
            'ie' => 'UTF-8',
            'q' => $text,
            'client' => 'tw-ob',
            'tl' => $this->lang
        ];

        return self::GOOGLE_TEXT_TO_SPEECH_URL . http_build_query($qParams);
    }
}
