<?php

return [
    'frontend' => [
        'pixelconsult/ki-chatbot-embed' => [
            'target' => \Pixelconsult\KiChatbot\Middleware\ChatbotEmbedMiddleware::class,
            'description' => 'Injects the KI Chatbot embed script into frontend HTML responses',
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'before' => [
                'typo3/cms-frontend/output-compression',
            ],
        ],
    ],
];
