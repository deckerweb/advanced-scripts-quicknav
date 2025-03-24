# Advanced Scripts QuickNav (for the _Advanced Scripts_ Plugin)

![Snippets QuickNav plugin in action](https://raw.githubusercontent.com/deckerweb/advanced-scripts-quicknav/master/assets-github/advanced-scripts-quicknav-screenshot.png)

The **Advanced Scripts QuickNav** plugin adds a quick-access navigator (aka QuickNav) to the WordPress Admin Bar (Toolbar). It allows easy access to your Code Snippets listed by Active, Inactive, Snippet Type or Tag. Safe Mode is supported. Comes with inspiring links to snippet libraries.

### Tested Compatibility
- **Latest Advanced Scripts**: 2.5.2
- **WordPress**: 6.7.2 / 6.8 Beta
- **PHP**: 8.0 – 8.3

---

[Support Project](#support-the-project) | [Installation](#installation) | [How Plugin Works](#how-this-plugin-works) | [Custom Tweaks](#custom-tweaks) | [Changelog](#changelog--releases) | [Plugin's Backstory](#plugins-backstory) | [Plugin Scope / Disclaimer](#plugin-scope--disclaimer)

---

## Support the Project

If you find this project helpful, consider showing your support by buying me a coffee! Your contribution helps me keep developing and improving this plugin.

Enjoying the plugin? Feel free to treat me to a cup of coffee ☕🙂 through the following options:

- [![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/W7W81BNTZE)
- [Buy me a coffee](https://buymeacoffee.com/daveshine)
- [PayPal donation](https://paypal.me/deckerweb)

---

## Installation

#### **Quick Install – as Plugin**
1. **Download ZIP:** [**advanced-scripts-quicknav.zip**](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/advanced-scripts-quicknav.zip)
2. Upload via WordPress Plugins > Add New > Upload Plugin
3. Once activated, you’ll see the **Scripts** menu item in the Admin Bar.

#### **Alternative: Use as Script (Code Snippet)**
1. Below, download the appropriate snippet version
2. activate or deactivate in your snippets plugin

[**Download .json**](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/ddw-advanced-scripts-quicknav.code-snippets.json) version for _Advanced Scripts_ just use the "Import" feature.

#### Minimum Requirements 

* WordPress version 6.7 or higher
* PHP version 7.4 or higher (better 8.3+)
* MySQL version 8.0 or higher / OR MariaDB 10.1 or higher
* Administrator user with capability `manage_options` and `activate_plugins`

---

## How this Plugin Works

1. **Your Scripts/ Code Snippets in the Admin Bar**: various listings – Active scripts, Inactive scripts, by folder (including subfolder)
2. **Additional Links**:
	- _Snippets_: Code snippet libraries for WordPress by various authors, including the official Code Snippets Cloud
	- _Plugin ecosystem_: Links to resources like the Code Snippets website, Docs, Learning, Emergency fixes etc., plus Facebook group.
	- _About_: Includes links to the plugin author.
3. Support for Advanced Scripts own "Safe Mode" – extra notice in Admin Bar
4. Support for WordPress own "Script Debug" constant - extra notice in Admin Bar
5. Third-party plugin support/integration (currently: _System Dashboard_ by Bowo / _Variable Inspector_ by Bowo / _Debug Log Manager_ by Bowo)
6. Plugin installation mode:
	- a) As regular plugin (support translations then)
	- b) As a script/ code snippet - directly in _Advanced Scripts_ itself! 👏
7. Custom tweaks via constants: enable or disable various additional features or tweaks – just as simple code snippets, see below --- this keeps the plugin/snippet simple and lightweight
8. Show the Admin Bar also in Block Editor full screen mode.

---

## Custom Tweaks via Constants

### Default capability (aka permission)
The intended usage of this plugin is for Administrator users only. Therefore the default capability to see the new Admin Bar node is set to `activate_plugins`. You can change this via the constant `ASQN_VIEW_CAPABILITY` – define that via `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_VIEW_CAPABILITY', 'activate_plugins' );
```

### Name of main menu item
The default is just "Snippets" – catchy and short. However, if you don't enjoy "Snippets" you can tweak that also via the constant `ASQN_NAME_IN_ADMINBAR` – define that also via `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_NAME_IN_ADMINBAR', 'Snippets' );
```

### Snippets count – addition to main menu item:
![With Counter -- Snippets QuickNav plugin](https://raw.githubusercontent.com/deckerweb/advanced-scripts-quicknav/master/assets-github/with-counter.png)
```
define( 'ASQN_COUNTER', 'yes' );
```

### Default icon of main menu item 
![Icon Alternatives -- Advanced Scripts QuickNav plugin](https://raw.githubusercontent.com/deckerweb/advanced-scripts-quicknav/master/assets-github/icon-alternatives.png)
Since the official plugin/company logo is a bit too complex for the Admin Bar, I created an icon myself. However, you can use two other alternatives: 1) Of course, the Advanced Scripts company logo if you want or 2) a more neutral "code" logo from Remix Icon (free and open source licensed!). You can also tweak that via a constant in `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_ICON', 'blue' );  // Advanced Scripts company logo
```
```
define( 'ASQN_ICON', 'remix' );  // code icon by Remix Icon
```

### Disable code snippets library items
Removes the "Find Snippets" section
```
define( 'ASQN_DISABLE_LIBRARY', 'yes' );
```

### Disable footer items (Links & About)
Removes the "Links" & "About" sections
```
define( 'ASQN_DISABLE_FOOTER', 'yes' );
```

### "Expert Mode"
This is enabled by default, hence the original plugin name _Advanced_ Scripts. It just adds some additional links for coders:
- _Site Health Info_ (WP Core)
- Plugin: _System Dashboard_ by Bowo
- Plugin: _Variable Inspector_ by Bowo
- Plugin: _Debug Log Manager_ by Bowo

If you don't want that just disable it via constant:
```
define( 'ASQN_EXPERT_MODE', FALSE );
```
Note: Support for _some_ additional stuff in that mode may come in future.

---

## Changelog / Releases

### 🎉 v1.0.0 (2025-03-24)
* Initial release
* Includes `.pot` file, plus packaged German translations

---

## Plugin's Backstory

_I needed (and wanted) this plugin (Advanced Scripts QuickNav) myself so I developed it. Since Code Snippets was first released in 2012 I am using it and loving it. On some sites I have up to 20 or 30 snippets, small stuff mostly, but sometimes bigger also. For a long time, I have wanted a way to get faster to specific snippets to maintain those (for whatever reason). Since I have long history of Admin Bar (Toolbar) plugins I thought that would be another one I could make. In the last few weeks I felt the need to finally code something. So I came up with this little helper plugin / "snippet". And, scratching my own itch is also always something enjoyable. My hope is, that you will enjoy it as well (the finished plugin)._

–– David Decker, plugin developer, in March of 2025

---

## Plugin Scope / Disclaimer

This plugin comes as is.

_Disclaimer 1:_ So far I will support the plugin for breaking errors to keep it working. Otherwise support will be very limited. Also, it will NEVER be released to WordPress.org Plugin Repository for a lot of reasons (ah, thanks, Matt!).

_Disclaimer 2:_ All of the above might change. I do all this stuff only in my spare time.

_Most of all:_ Blessed (snippet) coding, and have fun building great sites!!! 😉

---

Official plugin/company logo icon: © Clean Plugins
Icon used in promo graphics: [© Remix Icon](https://remixicon.com/)

Readme & Plugin Copyright © 2025 David Decker – DECKERWEB.de