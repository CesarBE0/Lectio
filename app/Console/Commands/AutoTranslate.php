<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslate extends Command
{
    // El nombre del comando que escribirás en la terminal
    protected $signature = 'traducir:auto {idioma : El código del idioma (en, fr, it, de...)}';

    protected $description = 'Traduce automáticamente el archivo JSON de idioma usando Google Translate';

    public function handle()
    {
        $targetLang = $this->argument('idioma');
        $filePath = lang_path("$targetLang.json");

        // 1. Verificar si el archivo existe
        if (!file_exists($filePath)) {
            $this->error("❌ El archivo lang/$targetLang.json no existe.");
            $this->info("Primero ejecuta: php artisan translatable:export $targetLang");
            return;
        }

        $this->info("🌍 Iniciando traducción automática al: " . strtoupper($targetLang));

        // 2. Leer el archivo JSON
        $jsonContent = file_get_contents($filePath);
        $translations = json_decode($jsonContent, true);

        // Configuramos el traductor (Detecta idioma origen automáticamente, o asume Español si tus claves están en español)
        $tr = new GoogleTranslate();
        $tr->setTarget($targetLang);

        $bar = $this->output->createProgressBar(count($translations));
        $bar->start();

        $updatedCount = 0;

        foreach ($translations as $key => $value) {
            // Solo traducimos si el valor está vacío O es igual a la clave (significa que no se ha traducido)
            // Y evitamos traducir claves técnicas como "auth.password" si no quieres
            if ($value === '' || $value === $key) {

                try {
                    // Traducir la CLAVE (que es el texto en español de tu código)
                    $translatedText = $tr->translate($key);

                    // Guardamos la traducción
                    $translations[$key] = $translatedText;
                    $updatedCount++;

                    // Una pequeña pausa para no saturar a Google
                    usleep(100000); // 0.1 segundos
                } catch (\Exception $e) {
                    // Si falla, lo dejamos igual
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // 3. Guardar el archivo actualizado
        // Usamos JSON_UNESCAPED_UNICODE para que los acentos se vean bien
        file_put_contents($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ ¡Listo! Se han traducido $updatedCount frases nuevas en lang/$targetLang.json");
    }
}
