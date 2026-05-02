# WP Title Case

[![CI](https://github.com/thisismyurl/wp-title-case/actions/workflows/ci.yml/badge.svg)](https://github.com/thisismyurl/wp-title-case/actions/workflows/ci.yml) [![WordPress Tested](https://img.shields.io/badge/WordPress-4.1%2B-blue)](https://wordpress.org/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)


Auto-applies title-case rules to WordPress page and post titles via the `the_title` filter — display only, no database writes.

## The short story

I wrote this plugin in 2008. In January 2016, Phill Coxon took it over and shepherded it through the WordPress 4.x era. The plugin's been quiet for a while and I've brought it back home to keep it working on modern WordPress.

That means: same plugin, same `the_title` filter approach, same minimum-letter-count exclusion, same harmless display-only behaviour. It just lives at [github.com/thisismyurl/wp-title-case](https://github.com/thisismyurl/wp-title-case) again and the maintenance is mine to do.

## What it does

- Capitalises words in post and page titles according to title-case conventions.
- Excludes short words (default: 2 letters or fewer) so "of", "to", "in", "the" stay lowercase.
- Display-only — uses the `the_title` filter, never modifies `wp_posts.post_title`.
- Works in browser titles, post/page titles, category and tag list titles, and feeds.

## What it doesn't do

- It doesn't enforce a particular style guide (AP, Chicago, MLA). It applies the simple rule: capitalise words above N letters.
- It doesn't override titles that contain explicit casing intentions like ALL CAPS or `iPhone` — for those, a per-post override with the `tag_alternatives` term meta is on the roadmap.

## Installation

- WordPress.org plugin directory (recommended): [WP Title Case](https://wordpress.org/plugins/wp-title-case/)
- Manual: download the latest release, upload to `wp-content/plugins/`, activate.

## Maintained by

Christopher Ross, on the open web since 1996 and on WordPress since 2007. More at [thisismyurl.com](https://thisismyurl.com/).

## License

GPL-2.0-or-later. The original copyright remains with Christopher Ross (2008); maintenance contributions through 2016 are credited to Phill Coxon in the changelog.
