<?php

use App\Kernel;

// Carga el autoloader de Composer
//PHP pueda cargar automáticamente las clases necesarias.
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Devuelve una función que crea el Kernel, el núcleo de Symfony. Le pasa dos datos que vienen de .env
return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};