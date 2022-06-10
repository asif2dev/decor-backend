<?php

namespace App\Modules\Scraper;

use DOMDocument;
use DOMElement;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class Mashreqy implements ScraperInterface
{
    private const BASE_URL = 'https://mashreqy.com';

    public function __construct(private Client $client, private LoggerInterface $logger)
    {
    }

    public function get(): \Generator
    {
        $catFromHtml = $this->getCategoriesFromHtml();
        $cats = $this->getCategories();

        $finalCats = $this->normalizeCategories($catFromHtml, $cats);
        dd($finalCats);
    }

    private function getCategories(): array
    {

        $result = [];
        $response = $this->client->get(sprintf('%s/collections.json?limit=1000', self::BASE_URL));
        $categories = json_decode($response->getBody()->getContents(), true);
        foreach ($categories['collections'] as $collection) {
            $cat = new Category();
            $cat->name = $collection['title'];
            $cat->link = sprintf('%s/collections/%s', self::BASE_URL, $collection['handle']);
            $cat->parent = '';
            $cat->productsCount = $collection['products_count'];

            $result[] = $cat;
        }

        return $result;
    }

    private function getCategoriesFromHtml(): array
    {
        $result = [];

        $response = $this->client->get(self::BASE_URL);
        $html = $response->getBody()->getContents();

        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $items = $dom->getElementsByTagName('ul');

        foreach ($items as $collection) {
            /** @var DOMElement $collection */
            $cssClass = $collection->getAttribute('class');
            if ($cssClass !== 'navmenu navmenu-depth-1') {
                continue;
            }

            foreach ($collection->childNodes as $node) {
                /** @var DOMElement $node */
                if ($node->nodeName !== 'li') {
                    continue;
                }

                /** @var DOMElement $rootNode */
                $rootNode = $node->getElementsByTagName('summary')[0];
                if (!$rootNode) {
                    continue;
                }

                $rootName = utf8_decode(trim(preg_replace('/\s\s+/', '', $rootNode->nodeValue)));
                $parent = $rootNode->parentNode->getElementsByTagName('ul')[0];

                foreach ($parent->getElementsByTagName('summary') as $parent) {
                    $parentLink = self::BASE_URL . trim(urldecode($parent->getAttribute('data-href')));
                    $parentName = utf8_decode(trim(preg_replace('/\s\s+/', '', $parent->nodeValue)));

                    foreach ($parent->parentNode->getElementsByTagName('a') as $child) {
                        $category = new Category();
                        $category->name = $parentName;
                        $category->link = $parentLink;
                        $category->parent = $rootName;

                        /** @var DOMElement $child */
                        $category->childName = utf8_decode(trim(preg_replace('/\s\s+/', '', $child->nodeValue)));
                        $category->childLink = self::BASE_URL . urldecode($child->getAttribute('href'));;

                        $result[] = $category;
                    }
                }
            }
        }

        return $result;
    }

    private function normalizeCategories(array $catFromHtml, array $cats): array
    {
        $result = [];
        foreach ($cats as $cat) {
            foreach ($catFromHtml as $htmlCat) {
                if ($htmlCat->childName === $cat->name) {
                    $category = new Category();
                    $category->name = $htmlCat->name;
                    $category->parent = $htmlCat->parent;
                    $category->link = $htmlCat->link;
                    $category->productsCount = $cat->productsCount;

                    $result[] = $category;
                }
            }
        }

        return $result;
    }
}
