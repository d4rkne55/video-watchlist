<?php

/**
 * This class is for parsing YouTube's HTML and get data out of it
 */
class Youtube extends VideoPageParser
{
    public function __construct($html) {
        if (!empty($html)) {
            libxml_use_internal_errors(true);

            $dom = new DOMDocument();
            $dom->loadHTML($html);
            $xPath = new DOMXPath($dom);

            $titleInfo = $xPath->query('head/meta[@name="title"]');
            $thumbnailInfo = $xPath->query('head/meta[@property="og:image"]');

            if (!$titleInfo->length) {
                throw new RuntimeException("HTML structure doesn't seem to be supported.");
            }

            if (!$thumbnailInfo->length) {
                throw new RuntimeException('Video not available.');
            }

            $this->title = $titleInfo->item(0)->getAttribute('content');

            $thumbnail = $thumbnailInfo->item(0)->getAttribute('content');
            $this->thumbnailUrl = substr($thumbnail, 0, strrpos($thumbnail, '/')) . '/hqdefault.jpg';

            libxml_clear_errors();
        }
    }
}
