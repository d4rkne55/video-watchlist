<?php

abstract class VideoPageParser
{
    protected $title;
    protected $thumbnailUrl;

    public function getTitle() {
        return $this->title;
    }

    public function getThumbnailUrl() {
        return $this->thumbnailUrl;
    }
}
