<?php

declare(strict_types=1);

namespace Pixelconsult\KiChatbot\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;

/**
 * Custom backend preview for the KI Chatbot Widget content element.
 * Shows the configured assistant ID and settings in the TYPO3 page module.
 */
class KiChatbotPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $assistantId = $record['tx_kichatbot_assistant_id'] ?? '';
        $baseUrl = $record['tx_kichatbot_base_url'] ?? '';
        $position = $record['tx_kichatbot_position'] ?? 'right';
        $theme = $record['tx_kichatbot_theme'] ?? 'light';
        $autoOpen = (bool)($record['tx_kichatbot_auto_open'] ?? false);

        if (empty($assistantId)) {
            return '<div style="padding:10px;color:#c00;font-weight:bold;">
                ⚠️ Keine Assistant-ID konfiguriert
            </div>';
        }

        $effectiveUrl = !empty($baseUrl) ? $baseUrl : 'https://my-chatify.de';

        $html = '<div style="padding:10px;background:#f8f9fa;border-radius:6px;margin:4px 0;">';
        $html .= '<div style="margin-bottom:6px;">';
        $html .= '<strong style="color:#6366f1;">🤖 KI Chatbot Widget</strong>';
        $html .= '</div>';
        $html .= '<table style="font-size:12px;line-height:1.6;">';
        $html .= '<tr><td style="padding-right:10px;color:#666;">Assistant-ID:</td>';
        $html .= '<td><code style="background:#e8e8e8;padding:1px 5px;border-radius:3px;">' . htmlspecialchars($assistantId) . '</code></td></tr>';
        $html .= '<tr><td style="padding-right:10px;color:#666;">Server:</td>';
        $html .= '<td>' . htmlspecialchars($effectiveUrl) . '</td></tr>';
        $html .= '<tr><td style="padding-right:10px;color:#666;">Position:</td>';
        $html .= '<td>' . htmlspecialchars(ucfirst($position)) . '</td></tr>';
        $html .= '<tr><td style="padding-right:10px;color:#666;">Theme:</td>';
        $html .= '<td>' . htmlspecialchars(ucfirst($theme)) . '</td></tr>';
        $html .= '<tr><td style="padding-right:10px;color:#666;">Auto-Open:</td>';
        $html .= '<td>' . ($autoOpen ? '✅ Ja' : '❌ Nein') . '</td></tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}
