<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | This file is where you may configure your CORS settings for your application.
    | The settings here will be used when handling requests that require CORS.
    |
    | The CORS configuration is primarily responsible for enabling or disabling
    | cross-origin requests to your application. You may configure CORS to
    | accept requests from different domains, set allowed headers, methods, etc.
    |
    */

    'paths' => ['api/*'],  // Applique CORS uniquement aux routes API

    'allowed_methods' => ['*'],  // Autorise toutes les méthodes HTTP (GET, POST, PUT, DELETE, etc.)

    'allowed_origins' => ['http://localhost:4200','https://test25.alwaysdata.net'],  // Autorise l'origine de votre application Angular

    'allowed_origins_patterns' => [],  // Vous pouvez utiliser des expressions régulières si nécessaire

    'allowed_headers' => ['*'],  // Autorise tous les en-têtes dans la requête

    'exposed_headers' => [],  // Si vous avez des en-têtes spécifiques à exposer à la réponse

    'max_age' => 0,  // Temps en secondes que les résultats CORS peuvent être mis en cache

    'supports_credentials' => false,  // Si vous devez envoyer des cookies ou des informations d'authentification, passez à true
];
