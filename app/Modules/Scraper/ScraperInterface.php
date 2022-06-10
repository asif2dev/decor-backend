<?php

namespace App\Modules\Scraper;

interface ScraperInterface
{
    public function get(): \Generator;
}
