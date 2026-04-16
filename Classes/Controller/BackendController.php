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

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'assistantId' => $assistantId,
            'isConfigured' => !empty($assistantId),
        ]);

        return $moduleTemplate->renderResponse('Backend/Index');
    }

    public function saveAction(): ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $assistantId = trim((string)($arguments['assistantId'] ?? ''));

        try {
            $current = $this->getExtensionConfig();
            $current['assistantId'] = $assistantId;

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
