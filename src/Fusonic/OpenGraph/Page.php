<?php

namespace Fusonic\OpenGraph;

class Page extends Element
{
    public $description;
    public $determiner;
    public $images = [];
    public $locale;
    public $localeAlternate = [];
    public $siteName;
    public $title;
    public $url;
    public $videos = [];

    public function __construct()
    {
    }
}
