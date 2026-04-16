<?php

use Pixelconsult\KiChatbot\Controller\BackendController;

/**
 * TYPO3 v12+ Backend Module Registration
 * Registers the KI Chatbot module in the admin sidebar.
 */
return [
    'kichatbot' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'admin',
        'workspaces' => 'live',
        'labels' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_mod.xlf',
        'icon' => 'EXT:ki_chatbot/Resources/Public/Icons/Extension.svg',
        'extensionName' => 'KiChatbot',
        'controllerActions' => [
            BackendController::class => [
                'index', 'save',
            ],
        ],
    ],
];
