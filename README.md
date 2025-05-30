# Advanced Scripts QuickNav (for the _Advanced Scripts_ Plugin)

![Snippets QuickNav plugin in action](https://raw.githubusercontent.com/deckerweb/advanced-scripts-quicknav/master/assets-github/advanced-scripts-quicknav-screenshot.png)

The **Advanced Scripts QuickNav** plugin adds a quick-access navigator (aka QuickNav) to the WordPress Admin Bar (Toolbar). It allows easy access to your Scripts & Code Snippets listed by Active, Inactive, Snippet Type or Tag. Safe Mode is supported. Comes with inspiring links to snippet libraries.

An awesome free add-on for [_Advanced Scripts_](https://r.freemius.com/6334/142255/) (premium) plugin!

#### Video Overview - Short Plugin Demo:
[![Advanced Script QuickNav Quick-Access from Your WordPress Admin Bar – Perfect Time Saver – Free Add-On](https://img.youtube.com/vi/PGtDhwAeTVY/0.jpg)](https://www.youtube.com/watch?v=PGtDhwAeTVY)

* Contributors: [David Decker](https://github.com/deckerweb), [contributors](https://github.com/deckerweb/advanced-scripts-quicknav/graphs/contributors)
* Tags: advanced scripts, quicknav, admin bar, toolbar, site builder, administrators, snippets, code snippets
* Requires at least: 6.7
* Requires PHP: 7.4
* Stable tag: [main](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest)
* Donate link: https://paypal.me/deckerweb
* License: GPL v2 or later

---

[Support Project](#support-the-project) | [Installation](#installation) | [Updates](#updates) | [Description](#description) | [FAQ](#frequently-asked-questions) | [Custom Tweaks](#custom-tweaks-via-constants) | [Changelog](#changelog) | [Plugin's Backstory](#plugins-backstory) | [Plugin Scope / Disclaimer](#plugin-scope--disclaimer)

---

## Support the Project 

If you find this project helpful, consider showing your support by buying me a coffee! Your contribution helps me keep developing and improving this plugin.

Enjoying the plugin? Feel free to treat me to a cup of coffee ☕🙂 through the following options:

- [![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/W7W81BNTZE)
- [Buy me a coffee](https://buymeacoffee.com/daveshine)
- [PayPal donation](https://paypal.me/deckerweb)
- [Join my **newsletter** for DECKERWEB WordPress Plugins](https://eepurl.com/gbAUUn)

Buy a copy of [_Advanced Scripts_](https://r.freemius.com/6334/142255/) (premium plugin) so I earn a small affiliate commission.

---

## Installation 

#### **Quick Install – as Plugin**
1. **Download ZIP:** [**advanced-scripts-quicknav.zip**](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/advanced-scripts-quicknav.zip)
2. Upload via WordPress Plugins > Add New > Upload Plugin
3. Once activated, you’ll see the **Scripts** menu item in the Admin Bar.

Note: You need [_Advanced Scripts_](https://r.freemius.com/6334/142255/) to be installed and activated to the new Admin Bar items!

#### **Alternative: Use as Script (Code Snippet)**
1. Below, download the appropriate snippet version
2. activate or deactivate in your snippets plugin

[**Download .json**](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/ddw-advanced-scripts-quicknav.as.json) version for _Advanced Scripts_ just use the "Import" feature.

--> Please decide for one of both alternatives!

#### Minimum Requirements 
* WordPress version 6.7 or higher
* PHP version 7.4 or higher (better 8.3+)
* MySQL version 8.0 or higher / OR MariaDB 10.1 or higher
* Administrator user with capability `manage_options` and `activate_plugins`

### Tested Compatibility 
- **Latest Advanced Scripts**: 2.5.2
- **WordPress**: 6.7.2 / 6.8 Beta
- **PHP**: 8.0 – 8.3

---

## Updates 

#### For Plugin Version:

1) Alternative 1: Just download a new [ZIP file](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/advanced-scripts-quicknav.zip) (see above), upload and override existing version. Done.

2) Alternative 2: Use the (free) [**_Git Updater_ plugin**](https://git-updater.com/) and get updates automatically.

3) Alternative 3: Upcoming! – In future I will built-in our own deckerweb updater. This is currently being worked on for my plugins. Stay tuned!

#### For Code Snippet Version:

Just manually: Download the latest Snippet version (see above) and import it in _Advanced Scripts_. – You can delete the old snippet; then just activate the new one. Done.

---

## Description 

### How this Plugin Works 

1. **Your Scripts/ Code Snippets in the Admin Bar**: various listings – Active scripts, Inactive scripts, by Folder (including Subfolder)
2. **Additional Links**:
	- _Snippets_: Code snippet libraries for WordPress by various authors, including the official Code Snippets Cloud
	- _Plugin ecosystem_: Links to resources like the Code Snippets website, Docs, Learning, Emergency fixes etc., plus Facebook group.
	- _About_: Includes links to the plugin author.
3. Support for Advanced Scripts own "Safe Mode" – extra notice in Admin Bar
4. Support for WordPress own "Script Debug" constant - extra notice in Admin Bar
5. Third-party plugin support/integration (currently: _DevKitPro_ by DPlugins / _System Dashboard_ by Bowo / _Variable Inspector_ by Bowo / _Debug Log Manager_ by Bowo)
6. Plugin installation mode:
	- a) As regular plugin (support translations then)
	- b) As a script/ code snippet - directly in _Advanced Scripts_ itself! 👏
7. Custom tweaks via constants: enable or disable various additional features or tweaks – just as simple code snippets, see below --- this keeps the plugin/snippet simple and lightweight (you can check the config in your WP install via: _Tools > Site Health > Info_ – there look for the row: _Advanced Scripts QuickNav (Plugin)_)
8. Show the Admin Bar also in Block Editor full screen mode.

---

## Frequently Asked Questions 

### How can I change / tweak things?
Please see here under [**Custom Tweaks via Constants**](#custom-tweaks-via-constants) what is possible!

### Why is this functionality not baked into _Advanced Scripts_ itself?
I don't know. Not everything needs to be built-in. That's what plugins are for: those who _need_ this functionality can install and use them. Or better, [just use it as code snippet](#installation) in _Advanced Scripts_ itself. Done :-)

### Why did you create this plugin?
Because I needed (and wanted!) it myself for the sites I maintain. [Read the backstory here ...](#plugins-backstory)

### Why is this plugin not on wordpress.org plugin repository?
Because the restrictions there for plugin authors are becoming more and more. It would be possible but I don't want that anymore. The same for limited support forums for plugin authors on .org. I have decided to leave this whole thing behind me.

---

## Custom Tweaks via Constants

### Default capability (aka permission)
The intended usage of this plugin is for Administrator users only. Therefore the default capability to see the new Admin Bar node is set to `activate_plugins`. You can change this via the constant `ASQN_VIEW_CAPABILITY` – define that via `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_VIEW_CAPABILITY', 'activate_plugins' );
```

### Restrict to defined user IDs only (since v1.1.0)
You can define an array of user IDs (can also be only _one_ ID) and that way restrict showing the Snippets Admin Bar item only for those users. Define that via `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_ENABLED_USERS', [ 1, 500, 867 ] );
```
This would enable only for the users with the IDs 1, 500 and 867. Note the square brackets around, and no single quotes, just the ID numbers.

For example you are one of many admin users (role `administrator`) but _only you_ want to show it _for yourself_. Given you have user ID 1:
```
define( 'ASQN_ENABLED_USERS', [ 1 ] );
```
That way only you can see it, the other admins can't!

### Name of main menu item
The default is just "Snippets" – catchy and short. However, if you don't enjoy "Snippets" you can tweak that also via the constant `ASQN_NAME_IN_ADMINBAR` – define that also via `wp-config.php` or via Advanced Scripts plugin:
```
define( 'ASQN_NAME_IN_ADMINBAR', 'Snippets' );
```

### Snippets count – addition to main menu item:
```
define( 'ASQN_COUNTER', 'yes' );
```

### Default icon of main menu item 
![Icon Alternatives -- Advanced Scripts QuickNav plugin](https://raw.githubusercontent.com/deckerweb/advanced-scripts-quicknav/master/assets-github/icon-alternatives.png)
Since the official plugin/company logo is a bit too complex for the Admin Bar, I created an icon myself. However, you can use two other alternatives: 1) Of course, the Advanced Scripts company logo if you really want that or 2) a more neutral "code" logo from Remix Icon (free and open source licensed!). You can also tweak that via a constant in `wp-config.php` or via Advanced Scripts plugin:
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
- Plugin: _DevKit Pro_ by DPlugins
- Plugin: _System Dashboard_ by Bowo
- Plugin: _Variable Inspector_ by Bowo
- Plugin: _Debug Log Manager_ by Bowo

If you **don't want** that just **disable** it via constant:
```
define( 'ASQN_EXPERT_MODE', FALSE );
```
Note: Support for _some_ additional stuff in that mode may come in future.

---

## Changelog 

**The Releases**

### 🎉 v1.1.0 – 2025-04-05
* New: Optionally only enable for defined user IDs _(new custom tweak)_
* New: Installable and updateable via [Git Updater plugin](https://git-updater.com/)
* Improved: Admin Bar CSS for Block / Site Editor fullscreen mode
* Fix: PHP warning on frontend
* Fix: Minor styling issues for top-level item
* Update: `.pot` file, plus packaged German translations, now including new `l10n.php` files!

### 🎉 v1.0.0 – 2025-03-24
* Initial release
* Includes some plugin support
* Includes `.pot` file, plus packaged German translations

---

## Plugin's Backstory 

_I needed (and wanted) this plugin (Advanced Scripts QuickNav) myself so I developed it. Since Advanced Scripts was first released in summer of 2020 I am using it and loving it. On some sites I have up to 20 or 30 snippets, small stuff mostly, but sometimes bigger also. For a long time, I have wanted a way to get faster to specific snippets to maintain those (for whatever reason). Since I have long history of Admin Bar (Toolbar) plugins I thought that would be another one I could make. In the last few weeks I felt the need to finally code something. So I came up with this little helper plugin / "snippet". And, scratching my own itch is also always something enjoyable. My hope is, that you will enjoy it as well (the finished plugin)._

–– David Decker, plugin developer, in March of 2025

---

## Plugin Scope / Disclaimer 

This plugin comes as is.

_Disclaimer 1:_ So far I will support the plugin for breaking errors to keep it working. Otherwise support will be very limited. Also, it will NEVER be released to WordPress.org Plugin Repository for a lot of reasons (ah, thanks, Matt!).

_Disclaimer 2:_ All of the above might change. I do all this stuff only in my spare time.

_Most of all:_ Blessed (snippet) coding, and have fun building great sites!!! 😉

---

Links to [_Advanced Scripts_](https://r.freemius.com/6334/142255/) may be affiliate links.

Official _Advanced Scripts_ plugin/company logo icon: © Clean Plugins by Abdelouahed Errouaguy

Icons used in Admin Bar items: [© Remix Icon](https://remixicon.com/)

Icons used in promo graphics: [© Remix Icon](https://remixicon.com/)

Readme & Plugin Copyright: © 2025, David Decker – DECKERWEB.de