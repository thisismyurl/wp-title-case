# This Is My URL - WP Title Case

[![CI](https://github.com/thisismyurl/wp-title-case/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/wp-title-case/actions/workflows/ci.yml) [![WordPress Tested](https://img.shields.io/badge/WordPress-4.1%2B-blue)](https://wordpress.org/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Auto-applies title-case rules to WordPress page and post titles via the `the_title` filter — display only, no database writes. Perfect for content teams that want consistent, professional headline formatting without manual editing.

## The Short Story

I wrote this plugin in 2008. In January 2016, Phill Coxon took it over and shepherded it through the WordPress 4.x era. The plugin went quiet for a while, but I've brought it back home to keep it working on modern WordPress.

Same plugin, same `the_title` filter approach, same minimum-letter-count exclusion, same harmless display-only behaviour. It just lives at [github.com/thisismyurl/wp-title-case](https://github.com/thisismyurl/wp-title-case) again and the maintenance is mine.

## What It Does

- **Capitalises words** in post and page titles according to title-case conventions.
- **Excludes short words** (default: 2 letters or fewer) so "of", "to", "in", "the" stay lowercase.
- **Display-only** — uses the `the_title` filter, never modifies `wp_posts.post_title`.
- **Works everywhere** — browser titles, post/page titles, category and tag lists, and feeds.

## What It Doesn't Do

- It doesn't enforce a particular style guide (AP, Chicago, MLA). It applies the simple rule: capitalise words above N letters.
- It doesn't enforce DB-level content edits. All transforms happen at render time via WordPress title filters.

## Requirements

- WordPress 5.0+
- PHP 7.4+

## Installation

**WordPress.org plugin directory (recommended):**
- Install directly from WordPress admin: **Plugins > Add New** → search "WP Title Case" → **Install Now**
- Or visit [WP Title Case on WordPress.org](https://wordpress.org/plugins/wp-title-case/)

**Manual installation:**
1. Download the latest release from [Releases](https://github.com/thisismyurl/wp-title-case/releases)
2. Unzip to `/wp-content/plugins/wp-title-case/`
3. Activate through **Plugins > Installed Plugins**

Once activated, the plugin works immediately with default settings. No configuration needed.

## Standards

- Direct access protection with ABSPATH checks.
- Nonce and capability checks for all admin actions.
- Aligned with WordPress coding standards.
- Legacy common-class scaffolding has been removed; plugin lifecycle hooks now live in the main plugin class.

---

## Support and Contribute

### Ways to Support

I'm building these tools because WordPress developers and site owners deserve straightforward, practical solutions. There's no tracking, no ads, and you don't need to pay to use these plugins.

If you find them helpful, here are some genuine ways to support the work:

- **Sponsor if it fits your budget:** You can sponsor the project through [GitHub Sponsors](https://github.com/sponsors/thisismyurl). Sponsorship helps, but it's always optional.
- **Contribute code or ideas:** Opening a pull request, reporting an issue, or testing edge cases is just as valuable as sponsorship.
- **Share your experience:** A follow on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps others discover this work.

### Report Issues and Questions

Found a bug? Want to suggest a feature? Questions about how it works?

- **File an issue:** Use the [Issues](../../issues) tab. Include your WordPress and PHP version, and steps to reproduce.
- **Start a discussion:** Use the [Discussions](../../discussions) tab for questions, ideas, or general conversation.

### Contributing Code

Code contributions are welcome and genuinely valuable:

1. **Fork this repository** and clone it locally.
2. **Create a feature branch** with a clear name (e.g., `feature/add-style-guide-option`).
3. **Make your changes** and test thoroughly on WordPress 4.1+ and modern versions.
4. **Follow WordPress coding standards** — run `composer run lint:phpcs` before opening a PR.
5. **Open a pull request** with a clear description of what changed and why.

---

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — original author (2008), current maintainer
- **Phill Coxon** — maintainer 2016–2019
- **Contributors:** Thanks to everyone who's reported issues and tested edge cases

## License

GPL-2.0-or-later. The original copyright remains with Christopher Ross (2008); maintenance contributions through 2016 are credited to Phill Coxon.

---

**More about This Is My URL:** Christopher Ross is a WordPress developer on the open web since 1996 and on WordPress since 2007. Learn more at [thisismyurl.com](https://thisismyurl.com/).


---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*

