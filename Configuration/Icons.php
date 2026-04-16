<?php

/**
 * TYPO3 v12+ Icon Registration
 * Registers icons used by the KI Chatbot extension.
 */
return [
    'ki_chatbot_module' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:ki_chatbot/Resources/Public/Icons/Extension.svg',
    ],
    'ki_chatbot_widget' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:ki_chatbot/Resources/Public/Icons/Extension.svg',
    ],
];
