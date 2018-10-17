<?php

use Framework\UrlParser;

class Scraper
{
    public $url;
    public $html;
    private $parser = null;

    public function __construct($url) {
        $url = new UrlParser($url);

        switch ($url->domain) {
            case 'youtu.be':
                $this->parser = 'Youtube';
                $this->url = 'https://www.youtube.com/watch?v=' . ltrim($url->path, '/');
                break;
            case 'www.youtube.com':
                $this->parser = 'Youtube';
                $this->url = $url->getBaseUrl();
                $this->url .= '?v=' . (string) $url->query->v;
                break;
            default:
                throw new Exception('Unsupported domain given. No parser available.');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);

        $this->html = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($statusCode >= 400) {
            throw new RuntimeException('', $statusCode);
        }
    }

    public function parse() {
        // fix for utf-8 documents without set charset
        // the DOM parser mangles up the encoding otherwise
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'utf-8');

        return new $this->parser($html);
    }
}
