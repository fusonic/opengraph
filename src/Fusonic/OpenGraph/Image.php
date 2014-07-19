<?php

namespace Fusonic\OpenGraph;


class Image extends Element
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
