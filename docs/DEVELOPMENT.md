# Development

[Deutsch](DEVELOPMENT-de.md)

PHP 8.0+ and Python 3 are sufficient to build the source checkout. No JavaScript bundler or Composer installation is needed.

```sh
php tests/smoke.php
python3 tools/build.py
```

`dist/advanced-scripts-quicknav.zip` is the stable-slug plugin archive. The versioned ZIP has identical content. The generated `.as.json` is an Advanced Scripts import, not a WordPress plugin ZIP. Import it inactive, then enable it with `plugins_loaded`, priority 20. The generated `.php` is the readable single-file equivalent.

The plugin-mode bootstrap resolves scripts after all active plugins have loaded. The adapter uses `get_scripts()` and keeps only metadata. Navigation builds an indexed, guarded projection while preserving upstream order; it never calls Advanced Scripts execution methods. Favorite metadata is scoped to the current user and blog ID.

The shared Library elects one highest-version runtime at `plugins_loaded`. Register it only from the actual plugin. The shared updater is initialized at `init`, with host-specific package identity, version and requirements checks. Neither component is included in the standalone snippet.

GitHub workflow builds artifacts; it does not publish a release. Use a draft/prerelease for candidates and publish a stable package after author approval and focused release checks; document the deferred integration matrix. Review bilingual changelogs and version strings when bumping the release. The updater skips drafts and prereleases.

`tests/smoke.php` uses WordPress doubles. It checks meaningful navigation, ancestor status, metadata scope, permission gates and menu limits, but cannot establish WordPress runtime compatibility. See VALIDATION.md for performed checks and limits.

SVG source artwork and PNG exports are bundled in `assets-github/`. English and German banners are separate assets. Historical asset URLs in `assets/` use the selected Code Compass artwork (A).

Translations ship as PO, MO and PHP catalogs. Keep both German informal and formal catalogs in sync with the POT source template. A missing translation must not be marked as verified compatibility.
