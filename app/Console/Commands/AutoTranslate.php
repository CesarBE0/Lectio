<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslate extends Command
{
    protected $signature = 'traducir:auto {idioma : El código del idioma (en, fr, it, de...)}';

    protected $description = 'Traduce automáticamente el archivo JSON de idioma usando Google Translate';

    public function handle()
    {
        $targetLang = $this->argument('idioma');
        $filePath = lang_path("$targetLang.json");

        if (!file_exists($filePath)) {
            $this->error("❌ El archivo lang/$targetLang.json no existe.");
            $this->info("Primero ejecuta: php artisan translatable:export $targetLang");
            return;
        }

        $this->info("🌍 Iniciando traducción automática al: " . strtoupper($targetLang));

        $jsonContent = file_get_contents($filePath);
        $translations = json_decode($jsonContent, true);

        $tr = new GoogleTranslate();
        $tr->setTarget($targetLang);

        $bar = $this->output->createProgressBar(count($translations));
        $bar->start();

        $updatedCount = 0;

        foreach ($translations as $key => $value) {
            if ($value === '' || $value === $key) {

                try {
                    $translatedText = $tr->translate($key);

                    $translations[$key] = $translatedText;
                    $updatedCount++;

                    usleep(100000);
                } catch (\Exception $e) {
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        file_put_contents($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ ¡Listo! Se han traducido $updatedCount frases nuevas en lang/$targetLang.json");
    }
}
