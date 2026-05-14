=== WP Title Case ===
Contributors: thisismyurl
Plugin URI: https://thisismyurl.com/downloads/wp-title-case/
Tags: titlecase, title-case, the_title, ucfirst, titles
Donate link: https://www.paypal.com/donate/?business=info%40thisismyurl.com&item_name=WP+Title+Case&currency_code=USD
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 0.6123

Auto-applies title-case rules to WordPress page and post titles via the_title filter — display only, no database writes.

== Description ==

Automatically format titles across your website — including the browser title, post and page titles, category and tag list titles, and feeds.

This plugin catches common title-case formatting mistakes by capitalising every word in a title above a configurable minimum length. Short words (by default, words of two letters or fewer such as "of", "to", "in") are left lowercase.

For example:

* "the quick brown fox" appears as "The Quick Brown Fox"
* "i love this plugin do you have more?" appears as "I Love This Plugin Do You Have More?"

It is **display-only**: the plugin uses `the_title` and `get_the_title` filters and never modifies `wp_posts.post_title` in the database.

= Per-post override =

Some titles are intentionally cased — `iPhone`, `WordPress`, `eBay`, `ALL CAPS` headlines. Each post and page now ships with a "Skip title-case transform on this post" checkbox in the editor sidebar. Tick it and the filter leaves that title alone.

= Maintenance lineage =

Originally written by Christopher Ross in 2008. Maintained by Phill Coxon between 2016 and 2024. Returned to Christopher Ross's stewardship in 2026.

== Installation ==

Upload the plugin folder to your `wp-content/plugins/` directory and activate from the Plugins admin screen, or install directly from the WordPress.org plugin directory.

Configure under **Settings → WP Title Case**.

== Screenshots ==

1. WordPress admin interface

== Frequently Asked Questions ==

= Where do I ask questions about this plugin? =

Open an issue on the [GitHub repository](https://github.com/thisismyurl/wp-title-case/issues) or use the WordPress.org support forums.

= How do I exclude certain words from the filter? =

Two ways:

1. Set a minimum word length under Settings → WP Title Case. Any word shorter than that length stays as-is.
2. Add specific words to the comma-separated **Ignored Words** list. Those words will never be capitalised.

= How do I skip the transform on a single post? =

Open the post in the editor and tick the "Skip title-case transform on this post" checkbox in the sidebar.

== Changelog ==

= 0.6123 =

* Refactor: removed legacy `thisismyurl-common.php` dependency and inlined shared hooks in the main plugin class.
* Security: removed cross-origin PayPal POST form (replaced with a plain donate anchor with `rel="noopener noreferrer"`).
* Security: sanitised `$_GET['page']` reads with `wp_unslash()` + `sanitize_key()`.
* Bug: fixed PHP 8+ fatal from undefined `$result_text_array`.
* Bug: corrected misspelled class name `thissimyurl_WPTitleCase` -> `thisismyurl_WPTitleCase`; the typo'd name remains as a `class_alias` for one release.
* Bug: removed two `register_uninstall_hook( 'uninstall.php', false )` calls; uninstall is now correctly handled by `uninstall.php`.
* Bug: removed dead `activate_plugin_name` / `deactivate_plugin_name` hooks (these were literal strings, not real hook names).
* i18n: replaced text-domain constant with literal `'wp-title-case'` so .pot generation actually picks the strings up.
* i18n: added missing modern plugin headers (License, License URI, Text Domain, Domain Path, Requires at least, Requires PHP, Update URI).
* Quality: escaped all admin output (`esc_html`, `esc_attr`, `esc_url`).
* Quality: split donate block out of the settings `<form>` to prevent context confusion.
* Quality: cast `min_word_length` to `int` with a sane default of 3.
* Quality: added `is_admin()` short-circuit and `thisismyurl_wp_title_case_should_apply` filter on the `the_title` filter.
* Quality: added Unicode-aware transform via `mb_convert_case` when `mbstring` is available.
* Feature: per-post `_wptc_skip` post meta with a meta-box checkbox to opt out of the transform per post.
* Repo: added `.gitignore`, `.distignore`, `CHANGELOG.md`, `SECURITY.md`, `CONTRIBUTING.md`, `CODEOWNERS`, `composer.json`, `phpcs.xml.dist`.
* Repo: removed `|| true` from CI (failures will now actually fail).
* Repo: corrected "Chrsitopher" typo across docblocks.

= 15.01 =

* added French language files
* added German language files
* moved plugin to OOP format
* rewrote core replace functions
* added uninstall functions
* upgraded to use Settings API

= 14.12 =

* setup language functions
* cleared a couple minor errors on PHP 5.1
* moved admin CSS to external pickup

= 1.6 =

* added the option to exclude words from the filter
* cleaned up code elements

= 1.5 =

* added formatting to RSS feeds
* added options page to control short word formatting
* added localization options for language files

= 1.2.1 =

* fix for 's

= 1.2.0 =

* added function to wp_title

= 1.1.0 =

* Updated cr_ to thisismyurl_ to maximize compatibility
* Tested with WordPress 3.3.1
* Removed comment from footer

= 1.0.3 =

* version update
* changed URL's

= 1.0.2 =

* Removed welcome message

= 1.0.1 =

* Added welcome message

= 1.0.0 =

* First release
