# Contributing to WP Title Case

Thanks for the interest. This is a small, focused plugin and contributions are
welcome — bug fixes, locale-aware improvements, and translations especially.

## Before you open a PR

1. Open an issue first if it's anything beyond a typo or one-line fix. Saves
   both of us time when scope or approach needs a quick chat.
2. Run the linter locally. The CI check is the same one:
   ```bash
   composer install
   composer run lint
   ```
3. Run a quick syntax check on every PHP file you touched:
   ```bash
   php -l path/to/file.php
   ```
4. Keep commits small and focused. One logical change per commit.

## Coding style

- WordPress Coding Standards (the `phpcs.xml.dist` ruleset is the source of
  truth). Tabs, not spaces. Yoda conditions where the linter wants them.
- Escape on output (`esc_html`, `esc_attr`, `esc_url`, `esc_textarea`,
  `wp_kses_post`); sanitise on input. The two are different jobs.
- Capability checks before anything privileged (`current_user_can()`).
- Nonces on every state-changing form (`wp_nonce_field` /
  `wp_verify_nonce`).
- Text domain is the literal string `'wp-title-case'`. Never a constant.

## Translations

Drop `.po` / `.mo` files into `/langs` named
`wp-title-case-<locale>.po` and `wp-title-case-<locale>.mo`. The maintainer
will gladly review and merge. Translations through translate.wordpress.org are
also welcome and propagate automatically once published.

## Reporting a security issue

See `SECURITY.md`. Do **not** open a public GitHub issue for security
reports — email `security@thisismyurl.com` instead.

## Maintainer

Christopher Ross — [thisismyurl.com](https://thisismyurl.com/).
