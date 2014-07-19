<?php

namespace Fusonic\OpenGraph;


class Video extends Element
{
    public $url;
    public $secureUrl;
    public $type;
    public $width;
    public $height;

    public function __construct($url)
    {
        $this->url = $url;
    }
}
