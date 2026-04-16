<?php

defined('TYPO3') or die();

(static function () {
    // -------------------------------------------------------
    // 1. Register new CType "ki_chatbot_widget"
    // -------------------------------------------------------
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.CType.ki_chatbot_widget',
            'ki_chatbot_widget',
            'ki_chatbot_widget',
            'special',
        ]
    );

    // -------------------------------------------------------
    // 2. Define which fields to show for this CType
    // -------------------------------------------------------
    $GLOBALS['TCA']['tt_content']['types']['ki_chatbot_widget'] = [
        'previewRenderer' => \Pixelconsult\KiChatbot\Preview\KiChatbotPreviewRenderer::class,
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.type,
                CType,
            --div--;LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tab.chatbot_settings,
                tx_kichatbot_assistant_id,
                tx_kichatbot_base_url,
                tx_kichatbot_position,
                tx_kichatbot_theme,
                tx_kichatbot_auto_open,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.access,
                hidden,
                starttime,
                endtime,
                fe_group,
        ',
    ];

    // -------------------------------------------------------
    // 3. Define custom columns (TCA field configuration)
    // -------------------------------------------------------
    $customColumns = [
        'tx_kichatbot_assistant_id' => [
            'exclude' => false,
            'label' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_assistant_id',
            'description' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_assistant_id.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim,required',
                'placeholder' => '3f8a900503fed',
            ],
        ],
        'tx_kichatbot_base_url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_base_url',
            'description' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_base_url.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'https://my-chatify.de',
            ],
        ],
        'tx_kichatbot_position' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_position',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_position.right', 'right'],
                    ['LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_position.left', 'left'],
                ],
                'default' => 'right',
            ],
        ],
        'tx_kichatbot_theme' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_theme',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_theme.light', 'light'],
                    ['LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_theme.dark', 'dark'],
                ],
                'default' => 'light',
            ],
        ],
        'tx_kichatbot_auto_open' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ki_chatbot/Resources/Private/Language/locallang_db.xlf:tt_content.tx_kichatbot_auto_open',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 0,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $customColumns);

    // -------------------------------------------------------
    // 4. Register icon for the CType (fallback for TYPO3 v11;
    //    v12+ uses Configuration/Icons.php)
    // -------------------------------------------------------
    if (!class_exists(\TYPO3\CMS\Core\Imaging\IconRegistry::class)) {
        // Should not happen, but guard anyway
    } else {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        if (!$iconRegistry->isRegistered('ki_chatbot_widget')) {
            $iconRegistry->registerIcon(
                'ki_chatbot_widget',
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:ki_chatbot/Resources/Public/Icons/Extension.svg']
            );
        }
    }
})();
