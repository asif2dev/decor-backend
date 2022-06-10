<?php

namespace App\Console\Commands;

use App\Modules\Scraper\Item;
use App\Modules\Scraper\MacknMall;
use App\Modules\Scraper\Mashreqy;
use App\Modules\Scraper\ScraperInterface;
use Illuminate\Console\Command;
use League\Csv\AbstractCsv;
use League\Csv\Writer;

class ScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scraper';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private MacknMall $macknMall)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (file_exists('items.csv')) {
                unlink('items.csv');
            }

            $writer = Writer::createFromPath('items.csv', 'w+');
            $writer->insertOne([
                'link',
                'title',
                'price',
                'afterDiscount',
                'beforeDiscount',
                'image',
                'category',
                'parentCategory',
                'categoryLink'
            ]);


            $scrappers = [
//                MacknMall::class,
                Mashreqy::class
            ];

            foreach ($scrappers as $scrapper) {
                $this->generateAndWriteToCSV(
                    $writer,
                    $this->laravel->make($scrapper)
                );
            }


            $this->info('finished scraping...');
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    private function generateAndWriteToCSV(AbstractCsv $writer, ScraperInterface $scraper)
    {
        foreach ($scraper->get() as $item) {
            /** @var Item $item */
            if ($item === null) {
                continue;
            }

            $arr = $item->toArray();

            $record = [
                'link' => $arr['link'],
                'title' => $arr['title'],
                'price' => $arr['price']['price'],
                'afterDiscount' => $arr['price']['afterDiscount'],
                'beforeDiscount' => $arr['price']['beforeDiscount'],
                'image' => $arr['image'],
                'category' => $arr['category'],
                'parentCategory' => $arr['parentCategory'],
                'categoryLink' => $arr['categoryLink']
            ];

            $writer->insertOne($record);
        }
    }
}
