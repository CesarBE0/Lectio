<?php
return [
    'scan_directories' => [
        app_path(),
        resource_path('views'),
        resource_path('js'),
    ],

    'file_extensions' => [
        'php',
        'js',
        'vue',
        'jsx'
    ],

    'translation_methods' => [
        '__',
        'lang',
    ],
];
?>
