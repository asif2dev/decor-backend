<?php

namespace App\Modules\Scraper;

use DOMDocument;
use DOMElement;
use Generator;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class MacknMall implements ScraperInterface
{
    public const LINK = 'https://macknmall.com';

    public const EXCLUDE = [
        'chisels-and-punches',
        'air-compressors',
        'machines-washing-and-vacuums',
        'cars',

    ];

    public function __construct(private Client $client, private LoggerInterface $logger)
    {
    }

    public function get(): Generator
    {
        $categories = $this->getCategories();

        foreach ($categories as $category) {
            /** @var Category $category */
            if ($this->excluded($category->link) === true) {
                continue;
            }

            try {
                yield from $this->getItem($category);
            } catch (\Throwable $exception) {
                $this->logger->error(
                    'error while fetching items, retrying after 10s',
                    ['message' => $exception->getMessage()]
                );

                sleep(2);

                continue;
            }
        }

        yield null;
    }

    private function getCategories(): array
    {
        $response = $this->client->get(self::LINK);
        $html = $response->getBody()->getContents();
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $items = $dom->getElementsByTagName('ul');
        $result = [];
        foreach ($items as $link) {
            /** @var \DOMNode $link */
            $cssClass = $link->getAttribute('class');
            if ($cssClass !== 'groupdrop-link') {
                continue;
            }

            foreach ($link->childNodes as $node) {
                if ($node->nodeName !== 'li') {
                    continue;
                }

                $href = $this->getProperLink($node->childNodes[0]->getAttribute('href'));

                $cat = new Category();
                $cat->link = $href;
                $cat->name = $node->childNodes[0]->nodeValue;
                $cat->parent = $link->previousSibling->nodeValue;

                $result[] = $cat;
            }
        }

        return $result;
    }

    private function getItem(Category $item): Generator
    {
        $url = $this->getProperLink($item->link);
        $this->logger->info('fetching from: ' . $url);

        $response = $this->client->get($url);
        $html = $response->getBody()->getContents();
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $elems = $dom->getElementsByTagName('li');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if (str_contains($cssClass, 'product-item') === false) {
                continue;
            }

            $result = new Item();
            $result->category = $item->name;
            $result->parentCategory = $item->parent;
            $result->categoryLink = $item->link;

            $result->title = $this->getTitle($elem);
            if (empty($result->title)) {
                return null;
            }

            $result->link = $this->getLink($elem);
            $result->price = $this->getPrice($elem);
            $result->image = $this->getImage($elem);

            yield $result;
        }

        yield null;
    }

    private function getTitle(DOMElement $elem): ?string
    {
        $elems = $elem->getElementsByTagName('a');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if (str_contains($cssClass, 'product-item-link') === false) {
                continue;
            }

            if (empty(trim($elem->nodeValue))) {
                continue;
            }

            return $elem->nodeValue;
        }

        return null;
    }

    private function getPrice(DOMElement $elem): array
    {
        $afterDiscount = null;
        $beforeDiscount = null;
        $price = null;

        $elems = $elem->getElementsByTagName('div');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if (str_contains($cssClass, 'special-price-value') === false
                && str_contains($cssClass, 'old-price-value') === false
            ) {
                continue;
            }

            if (empty(trim($elem->nodeValue))) {
                continue;
            }

            if (str_contains($cssClass, 'special-price-value')) {
                $afterDiscount = $elem->nodeValue;
            } else {
                $beforeDiscount = $elem->nodeValue;
            }
        }

        $elems = $elem->getElementsByTagName('span');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if ($cssClass !== 'price') {
                continue;
            }

            if (empty(trim($elem->nodeValue))) {
                continue;
            }

            $price = $elem->nodeValue;
        }

        return [
            'price' => $price,
            'afterDiscount' => $afterDiscount,
            'beforeDiscount' => $beforeDiscount
        ];
    }

    private function getImage(DOMElement $elem): string
    {
        $elems = $elem->getElementsByTagName('img');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if (str_contains($cssClass, 'product-image-photo main-img') === false) {
                continue;
            }

            return $elem->getAttribute('src');
        }

        return '';
    }

    private function getLink(DOMElement $elem): string
    {
        $elems = $elem->getElementsByTagName('a');
        foreach ($elems as $elem) {
            $cssClass = $elem->getAttribute('class');
            if (str_contains($cssClass, 'product-item-link') === false) {
                continue;
            }

            return $elem->getAttribute('href');
        }

        return '';
    }

    private function getProperLink(string $link): string
    {
        return str_starts_with($link, 'http') ? $link : self::LINK . trim($link);
    }

    private function excluded(string $category): bool
    {
        foreach (self::EXCLUDE as $exclude) {
            if (str_contains($category, $exclude) === true) {
                return true;
            }
        }

        return false;
    }
}
