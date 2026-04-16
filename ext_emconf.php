<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'KI Chatbot (My-Chatify)',
    'description' => 'Embed the my-chatify.de AI chatbot widget on your TYPO3 website. Add a content element, enter your Chatbot Assistant ID, and the widget appears as a floating chat button for your visitors.',
    'category' => 'plugin',
    'author' => 'PixelConsult',
    'author_email' => 'info@pixelconsult.de',
    'author_company' => 'PixelConsult',
    'state' => 'stable',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
