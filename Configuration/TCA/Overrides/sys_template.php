<?php

defined('TYPO3') or die();

(static function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'ki_chatbot',
        'Configuration/TypoScript/',
        'KI Chatbot Widget'
    );
})();
