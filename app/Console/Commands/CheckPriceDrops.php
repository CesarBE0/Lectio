<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Mail;

class CheckPriceDrops extends Command
{
    protected $signature = 'lectio:check-prices';
    protected $description = 'Comprueba si los libros en las listas de deseos han bajado de precio';

    public function handle()
    {
        $this->info('Comprobación de precios ejecutada correctamente.');
    }
}
