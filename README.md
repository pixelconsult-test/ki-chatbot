# KI Chatbot Widget – TYPO3 Extension

Embed the **my-chatify.de** AI chatbot widget on your TYPO3 website with a simple content element.

## Requirements

- TYPO3 v11.5, v12.4, or v13.4
- A chatbot assistant created at [my-chatify.de](https://my-chatify.de)

## Installation

### Option A: Manual installation
1. Copy the entire `extension/` folder into your TYPO3 installation:
   ```
   typo3conf/ext/ki_chatbot/
   ```
2. In the TYPO3 backend, go to **Admin Tools → Extensions**
3. Activate the extension **"KI Chatbot Widget"**

### Option B: Composer (if published)
```bash
composer require pixelconsult/ki-chatbot
```

## Setup

### 1. Include the Static Template (TYPO3 v11 only)
- Go to **Web → Template** → your root template
- Click **"Edit the whole template record"**
- Under the **"Includes"** tab, add **"KI Chatbot Widget"** from the available items

> **Note:** On TYPO3 v12+ the TypoScript is included automatically.


## Usage

1. **Edit a page** in the TYPO3 backend
2. Click **"+ Content"** to add a new content element
3. Select **"KI Chatbot Widget"** (under the "Special" tab)
4. Enter your **Chatbot Assistant ID** (e.g., `3f8a900503fed`)
   - You can find this ID in your [my-chatify.de dashboard](https://my-chatify.de/dashboard/chatbots)

5. **Save** and view the page – the floating chatbot button will appear!

## What It Does

The extension renders a single `<script>` tag on your page:

```html
<script src="https://my-chatify.de/api/embed/chat/YOUR_ASSISTANT_ID" async></script>
```

This script:
- Loads the chatbot configuration from the server
- Creates a floating chat button (bottom-right or bottom-left)
- Opens an embedded chat window when clicked
- Works on all devices (responsive, full-screen on mobile)
- Requires **no additional CSS or JavaScript** on your side

## File Structure

```
ki_chatbot/
├── ext_emconf.php                          # Extension metadata
├── ext_localconf.php                       # Plugin registration
├── ext_tables.sql                          # Database schema (custom fields)
├── composer.json                           # Composer package info
├── README.md                               # This file
├── Classes/
│   └── Preview/
│       └── KiChatbotPreviewRenderer.php    # Backend page module preview
├── Configuration/
│   ├── TCA/
│   │   └── Overrides/
│   │       ├── tt_content.php              # CType + field definitions
│   │       └── sys_template.php            # Static template registration
│   └── TypoScript/
│       ├── constants.typoscript            # Default settings
│       └── setup.typoscript                # Rendering configuration
└── Resources/
    ├── Private/
    │   ├── Language/
    │   │   ├── locallang_db.xlf            # English labels
    │   │   └── de.locallang_db.xlf         # German labels
    │   └── Templates/
    │       └── KiChatbot.html              # Fluid template (renders <script>)
    └── Public/
        └── Icons/
            └── Extension.svg               # Extension icon
```

## Troubleshooting

| Problem | Solution |
|---|---|
| Widget doesn't appear | Check the Assistant ID is correct; verify the chatbot is trained and active in your dashboard |
| Wrong URL | Check the Base URL in TypoScript constants or the content element override field |
| Widget appears on wrong side | Check the Position setting in the content element |
| CORS errors in console | The embed endpoint should allow any origin; check server configuration |

## License

GPL-2.0-or-later
