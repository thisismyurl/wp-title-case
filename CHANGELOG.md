# Changelog

All notable changes to this project are documented here. This project follows
the [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format. Versions
use the `x.Yddd` scheme (release class . year-last-digit . Julian day) per the
maintainer's repo-wide convention; pre-release builds use `x=0`.

## [0.6123] - 2026-05-03

### Security
- Removed the cross-origin PayPal POST form. Donate is now a plain anchor with
  `rel="noopener noreferrer"`.
- Sanitised `$_GET['page']` reads with `wp_unslash()` and `sanitize_key()`.

### Fixed
- PHP 8+ fatal from undefined `$result_text_array` in the title-case loop.
- Misspelled class name `thissimyurl_WPTitleCase` corrected to
  `thisismyurl_WPTitleCase`. The typo'd name remains as a `class_alias` for
  one release to avoid breaking third-party code.
- Removed two `register_uninstall_hook( 'uninstall.php', false )` calls;
  uninstall is now correctly handled by the `uninstall.php` drop-in.
- Removed dead `activate_plugin_name` / `deactivate_plugin_name` hooks (these
  were literal strings, not real hook names).

### Changed
- Replaced the text-domain constant with the literal `'wp-title-case'`
  everywhere so `.pot` extraction actually picks the strings up.
- Added all required modern plugin headers (License, License URI, Text Domain,
  Domain Path, Requires at least, Requires PHP, Update URI).
- Escaped all admin output (`esc_html`, `esc_attr`, `esc_url`,
  `esc_textarea`).
- Split the donate / support block out of the settings `<form>` so the two
  contexts no longer share DOM neighbourhood.
- Cast `min_word_length` to `int` with a sane default of 3 (the previous
  empty-option path silently capitalised every word).
- Added an `is_admin()` short-circuit and a
  `thisismyurl_wp_title_case_should_apply` filter on the transform.
- Added a Unicode-aware transform via `mb_convert_case` when `mbstring` is
  available; `ucfirst` remains the fallback.
- Corrected "Chrsitopher" typo across docblocks.

### Added
- Per-post `_wptc_skip` post meta with a sidebar meta-box checkbox for posts
  whose titles have intentional casing (e.g. `iPhone`, brand names, ALL CAPS).
- `.gitignore`, `.distignore`, `CHANGELOG.md`, `SECURITY.md`,
  `CONTRIBUTING.md`, `CODEOWNERS`.
- `composer.json` and `phpcs.xml.dist` for local lint parity with CI.
- CI now actually fails on `php -l` errors (`|| true` removed).

## [15.01]

- Added French and German language files.
- Moved plugin to OOP format.
- Rewrote core replace functions.
- Added uninstall functions.
- Upgraded to use the Settings API.

Earlier history is preserved in `readme.txt`.
