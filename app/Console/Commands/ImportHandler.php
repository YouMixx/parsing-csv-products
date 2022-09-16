<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ImportHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:handler {filename} {chunk=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products for CSV file to DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->argument('chunk')) $chunk = $this->argument('chunk');

        $start = microtime(true);
        
        // Парсим с помощью библиотеки CSV таблицу
        $filename = storage_path('app/public/' . $this->argument('filename'));
        $data = new \SpreadsheetReader($filename);

        $insert_data = new Collection(); // Создаем коллекцию для данных
        foreach ($data as $key => $attr) {
            if($key == 0) continue; // Пропускаем первую строку с заголовками

            // Пушим в коллекцию данные в нужном формате
            $insert_data->push([
                'product_id' => $attr[0],
                'product_code' => $attr[1],
                'language' => $attr[2],
                'category' => $attr[3],
                'last_price' => $attr[4],
                'price' => $attr[5],
                'quantity' => $attr[6],
                'product_name' => $attr[7],
                'description' => $attr[8],
                'seo_name' => $attr[9],
                'short_description' => $attr[10],
                'status' => $attr[11],
                'vendor' => $attr[12],
                'features' => $attr[13],
            ]);

            if(is_int($key / $chunk)) {
                // Как только $chunk записей наберется в коллекции, вставляем их в БД и очищаем переменную.
                Product::upsert($insert_data->toArray(), ['product_id']);
                $insert_data = new Collection();
                $this->info("Вставили в БД $key записей");
            }
        }

        // Вставляем все что осталось
        Product::upsert($insert_data->toArray(), ['product_id']);

        // $chunks = $insert_data->chunk(1000);
        // Product::upsert($content, ['product_id']);
        // foreach ($chunks as $key => $chunk) {
        //     Product::upsert($chunk->toArray(), ['product_id']);
        //     // Product::insert($chunk->toArray());
        //     $this->info('Вставили в БД 1.000 записей');
        // }

        $end = microtime(true) - $start;
        $this->error('Time: ' . $end);

        return 0;
    }
}
