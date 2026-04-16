<?php

defined('TYPO3') or die();

(static function () {
    // Register the plugin for frontend rendering
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'KiChatbot',
        'Widget',
        [],
        []
    );

    // Add TypoScript automatically (for TYPO3 v12+)
    // For v11, editors include the static template manually
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:ki_chatbot/Configuration/TypoScript/setup.typoscript"'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '@import "EXT:ki_chatbot/Configuration/TypoScript/constants.typoscript"'
    );

    // Read Extension Configuration and pass values into TypoScript constants
    $extensionConfiguration = [];
    try {
        $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        )->get('ki_chatbot');
    } catch (\Exception $e) {
        // Use defaults if configuration is not yet saved
    }

    if (!empty($extensionConfiguration['assistantId'])) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            'plugin.tx_kichatbot.settings.assistantId = ' . $extensionConfiguration['assistantId']
        );
    }
})();
