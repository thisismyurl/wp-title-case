<?php
/**
 * Uninstall script for WP Title Case.
 *
 * Cleans up plugin options and post meta when the plugin is deleted via the
 * WordPress admin.
 *
 * @package   WP_Title_Case
 * @author    Christopher Ross
 * @copyright Copyright (c) 2008-2026, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


delete_option( 'thisismyurl_wp_title_case_min_word_length' );
delete_option( 'thisismyurl_wp_title_case_ignore_words' );
delete_option( 'thisismyurl_title_case' );

delete_metadata( 'post', 0, '_wptc_skip', '', true );
