<?php

declare(strict_types=1);

namespace Pixelconsult\KiChatbot\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PSR-15 Middleware that injects the KI Chatbot embed script
 * into every frontend HTML response, right before </body>.
 *
 * This approach is independent of TypoScript and works
 * reliably across TYPO3 v11, v12, and v13.
 */
class ChatbotEmbedMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Only inject into HTML responses
        $contentType = $response->getHeaderLine('Content-Type');
        if (stripos($contentType, 'text/html') === false) {
            return $response;
        }

        // Read extension configuration
        try {
            $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ki_chatbot');
        } catch (\Exception $e) {
            return $response;
        }

        // Check if assistant ID is configured
        $assistantId = trim($config['assistantId'] ?? '');
        if (empty($assistantId)) {
            return $response;
        }

        // Check if global embedding is enabled
        $enableGlobal = (bool)($config['enableGlobal'] ?? true);
        if (!$enableGlobal) {
            return $response;
        }

        // Build the embed script URL
        $baseUrl = rtrim(trim($config['baseUrl'] ?? 'https://my-chatify.de'), '/');
        $position = trim($config['defaultPosition'] ?? 'right');
        $theme = trim($config['defaultTheme'] ?? 'light');
        $language = trim($config['defaultLanguage'] ?? 'de');

        // Validate language against allowed values
        $allowedLanguages = ['auto', 'de', 'en', 'fr', 'es', 'it', 'nl'];
        if (!in_array($language, $allowedLanguages, true)) {
            $language = 'de';
        }

        $scriptUrl = $baseUrl . '/api/embed/chat/' . htmlspecialchars($assistantId, ENT_QUOTES, 'UTF-8');

        // Build query parameters (excluding lang for auto-mode)
        $params = [];
        if ($position && $position !== 'right') {
            $params[] = 'position=' . htmlspecialchars($position, ENT_QUOTES, 'UTF-8');
        }
        if ($theme && $theme !== 'light') {
            $params[] = 'theme=' . htmlspecialchars($theme, ENT_QUOTES, 'UTF-8');
        }

        if ($language === 'auto') {
            // Auto-detect: output an inline JS block that reads document.documentElement.lang
            if (!empty($params)) {
                $staticParams = implode('&', $params);
            } else {
                $staticParams = '';
            }
            $scriptTag = "\n<!-- KI Chatbot Widget by my-chatify.de -->\n"
                . "<script>\n"
                . "(function () {\n"
                . "    var supportedLangs = ['de', 'en', 'fr', 'es', 'it', 'nl'];\n"
                . "    var currentLang = document.documentElement.lang\n"
                . "        ? document.documentElement.lang.substring(0, 2).toLowerCase()\n"
                . "        : 'de';\n"
                . "    var chatbotLang = supportedLangs.indexOf(currentLang) !== -1 ? currentLang : 'de';\n"
                . "    var params = " . json_encode($staticParams ? explode('&', $staticParams) : [], JSON_UNESCAPED_UNICODE) . ";\n"
                . "    params.push('lang=' + chatbotLang);\n"
                . "    var s = document.createElement('script');\n"
                . "    s.src = '" . addslashes($scriptUrl) . "' + (params.length ? '?' + params.join('&') : '');\n"
                . "    s.async = true;\n"
                . "    document.body.appendChild(s);\n"
                . "}());\n"
                . "</script>\n";
        } else {
            // Fixed language: append lang param to URL
            $params[] = 'lang=' . htmlspecialchars($language, ENT_QUOTES, 'UTF-8');
            if (!empty($params)) {
                $scriptUrl .= '?' . implode('&', $params);
            }
            $scriptTag = "\n<!-- KI Chatbot Widget by my-chatify.de -->\n"
                . '<script src="' . $scriptUrl . '" async></script>' . "\n";
        }

        // Get the current HTML body
        $body = $response->getBody();
        $body->rewind();
        $html = $body->getContents();

        // Inject before </body> if it exists
        if (stripos($html, '</body>') !== false) {
            $html = str_ireplace('</body>', $scriptTag . '</body>', $html);
        } else {
            // Fallback: append to end
            $html .= $scriptTag;
        }

        // Create new response with modified body
        $newBody = new Stream('php://temp', 'rw');
        $newBody->write($html);

        return $response
            ->withBody($newBody)
            ->withHeader('Content-Length', (string)$newBody->getSize());
    }
}
