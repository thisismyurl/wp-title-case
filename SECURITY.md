# Security Policy

## Reporting a vulnerability

If you believe you've found a security issue in WP Title Case, please **do
not** open a public GitHub issue. Instead, email:

`security@thisismyurl.com`

Include:

- A description of the issue and the file/line(s) involved.
- Steps to reproduce on a clean WordPress install.
- The plugin version, WordPress version, and PHP version you tested against.
- Any proof-of-concept payload, kept minimal.

You should hear back within 72 hours. Coordinated disclosure timeline is 30
days from acknowledgement; longer if the fix requires a WordPress core change.

## Supported versions

Only the latest release on WordPress.org receives security fixes. If you're
running an older version, please update first.

## Scope

In scope:

- Cross-site scripting in the plugin's admin UI.
- Capability bypasses on the settings page.
- SQL injection (the plugin does not query the DB directly, but report
  anything you find).
- Anything that allows an unauthenticated user to change plugin options.

Out of scope:

- Issues that require an admin account to exploit (you already had the keys).
- Issues in third-party plugins or themes that interact with this plugin's
  filters.
- Best-practice hardening suggestions — please open a regular issue for those.
