<?php

declare(strict_types=1);

namespace Pixelconsult\KiChatbot\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Backend module controller for the KI Chatbot extension.
 */
class BackendController extends ActionController
{
    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {
    }

    public function indexAction(): ResponseInterface
    {
        $config = $this->getExtensionConfig();
        $assistantId = trim((string)($config['assistantId'] ?? ''));
        $rawLang = trim((string)($config['defaultLanguage'] ?? ''));
        $allowedLanguages = ['auto', 'de', 'en', 'fr', 'es', 'it', 'nl'];
        $defaultLanguage = (in_array($rawLang, $allowedLanguages, true) && $rawLang !== '') ? $rawLang : 'de';

        $languageOptions = [
            'de'   => 'Deutsch (DE) - Standard',
            'auto' => 'Automatisch - Seitensprache erkennen',
            'en'   => 'English (EN)',
            'fr'   => 'Francais (FR)',
            'es'   => 'Espanol (ES)',
            'it'   => 'Italiano (IT)',
            'nl'   => 'Nederlands (NL)',
        ];

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'assistantId' => $assistantId,
            'isConfigured' => !empty($assistantId),
            'defaultLanguage' => $defaultLanguage,
            'languageOptions' => $languageOptions,
        ]);

        return $moduleTemplate->renderResponse('Backend/Index');
    }

    public function saveAction(): ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $assistantId = trim((string)($arguments['assistantId'] ?? ''));
        $allowedLanguages = ['auto', 'de', 'en', 'fr', 'es', 'it', 'nl'];
        $defaultLanguage = trim((string)($arguments['defaultLanguage'] ?? 'de'));
        if (!in_array($defaultLanguage, $allowedLanguages, true)) {
            $defaultLanguage = 'de';
        }

        try {
            $current = $this->getExtensionConfig();
            $current['assistantId'] = $assistantId;
            $current['defaultLanguage'] = $defaultLanguage;

            GeneralUtility::makeInstance(ExtensionConfiguration::class)->set('ki_chatbot', $current);

            if (!empty($assistantId)) {
                $this->addFlashMessage('Chatbot ID gespeichert: ' . $assistantId, 'Gespeichert', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            } else {
                $this->addFlashMessage('Chatbot ID entfernt. Das Widget ist jetzt deaktiviert.', 'Gelöscht', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }
        } catch (\Exception $e) {
            $this->addFlashMessage('Fehler: ' . $e->getMessage(), 'Fehler', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }

        return $this->redirect('index');
    }

    protected function getExtensionConfig(): array
    {
        try {
            return (array)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ki_chatbot');
        } catch (\Exception $e) {
            return [];
        }
    }
}
