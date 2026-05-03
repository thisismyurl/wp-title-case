<?php
/**
 * Plugin Name:       WP Title Case
 * Plugin URI:        https://thisismyurl.com/downloads/wp-title-case/
 * Description:       Automatically applies title-case rules to WordPress page and post titles via the_title filter (display only, no DB writes).
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * Version:           0.6123
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-title-case
 * Domain Path:       /langs
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Update URI:        https://wordpress.org/plugins/wp-title-case/
 *
 * @package   WP_Title_Case
 * @author    Christopher Ross
 * @copyright Copyright (c) 2008-2026, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 */

/* If the plugin is called directly, die. */
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'THISISMYURL_WPTC_NAME', 'WP Title Case' );
define( 'THISISMYURL_WPTC_SHORTNAME', 'WP Title Case' );

define( 'THISISMYURL_WPTC_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_WPTC_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_WPTC_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_WPTC_NAMESPACE', basename( THISISMYURL_WPTC_FILENAME, '.php' ) );
define( 'THISISMYURL_WPTC_TEXTDOMAIN', 'wp-title-case' );

define( 'THISISMYURL_WPTC_VERSION', '0.6123' );

require_once __DIR__ . '/thisismyurl-common.php';

/**
 * Core class for WP Title Case.
 *
 * Hooks the_title filter and applies title-case rules to display output only.
 *
 * @since 15.01
 */
if ( ! class_exists( 'thisismyurl_WPTitleCase' ) ) {
	/**
	 * WP Title Case main class.
	 */
	class thisismyurl_WPTitleCase extends thisismyurl_Common_WPTC {

		/**
		 * Register the_title filter callbacks.
		 *
		 * @return void
		 */
		public function run() {

			add_filter( 'the_title', array( $this, 'title_case' ), 999, 2 );
			add_filter( 'get_the_title', array( $this, 'title_case' ), 999, 2 );
			add_filter( 'the_title_rss', array( $this, 'title_case' ), 999 );
		}



		/**
		 * Apply title-case rules to a string.
		 *
		 * @param string|null $text_to_alter Title string.
		 * @param int|null    $post_id       Post ID (when available via the_title).
		 * @return string Transformed title.
		 */
		public function title_case( $text_to_alter = null, $post_id = null ) {

			$result_text = (string) $text_to_alter;

			if ( '' === $result_text ) {
				return $result_text;
			}

			/**
			 * Filter whether title-case should apply to this title.
			 *
			 * Return false to short-circuit the transform for this call.
			 *
			 * @since 0.6123
			 *
			 * @param bool        $apply       Whether to apply the transform.
			 * @param string      $result_text The title string about to be processed.
			 * @param int|null    $post_id     Post ID, when known.
			 */
			$apply = apply_filters( 'thisismyurl_wp_title_case_should_apply', true, $result_text, $post_id );

			if ( ! $apply ) {
				return $result_text;
			}

			/* Skip in admin context — editors should see the title they actually saved. */
			if ( is_admin() && ! wp_doing_ajax() ) {
				return $result_text;
			}

			/* Per-post opt-out via _wptc_skip post meta. */
			if ( $post_id && get_post_meta( (int) $post_id, '_wptc_skip', true ) ) {
				return $result_text;
			}

			/* Fetch the current options. */
			$ignore_words      = get_option( 'thisismyurl_wp_title_case_ignore_words', '' );
			$min_word_length   = (int) get_option( 'thisismyurl_wp_title_case_min_word_length', 3 );
			$ignore_words_list = array();

			if ( ! empty( $ignore_words ) ) {
				$ignored_word_array = explode( ',', trim( (string) $ignore_words ) );

				foreach ( $ignored_word_array as $ignored_word_item ) {
					$ignore_words_list[] = strtolower( trim( $ignored_word_item ) );
				}
			}

			$altered_text_array = explode( ' ', $result_text );
			$result_text_array  = array();

			foreach ( $altered_text_array as $word_to_test ) {

				$word_compare = function_exists( 'mb_strtolower' )
					? mb_strtolower( $word_to_test, 'UTF-8' )
					: strtolower( $word_to_test );

				$word_length = function_exists( 'mb_strlen' )
					? mb_strlen( $word_to_test, 'UTF-8' )
					: strlen( $word_to_test );

				if ( ! in_array( $word_compare, $ignore_words_list, true ) && $word_length >= $min_word_length ) {
					$result_text_array[] = function_exists( 'mb_convert_case' )
						? mb_convert_case( $word_to_test, MB_CASE_TITLE, 'UTF-8' )
						: ucfirst( $word_to_test );
				} else {
					$result_text_array[] = $word_to_test;
				}
			}

			return implode( ' ', $result_text_array );
		}
	}
}

/**
 * Backwards-compatible alias for the historical typo'd class name.
 *
 * The original class shipped as `thissimyurl_WPTitleCase` (extra `s`). This
 * alias keeps any third-party code that referenced the typo'd name working
 * for one release. Will be removed in a subsequent release.
 *
 * @deprecated 0.6123 Use thisismyurl_WPTitleCase instead.
 */
if ( ! class_exists( 'thissimyurl_WPTitleCase' ) ) {
	class_alias( 'thisismyurl_WPTitleCase', 'thissimyurl_WPTitleCase' );
}

global $thisismyurl_WPTitleCase;

$thisismyurl_WPTitleCase = new thisismyurl_WPTitleCase();
$thisismyurl_WPTitleCase->run();

/**
 * Backwards-compatible global for the historical typo'd variable name.
 *
 * @deprecated 0.6123
 */
$GLOBALS['thissimyurl_WPTitleCase'] = $thisismyurl_WPTitleCase; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound


if ( ! function_exists( 'thisismyurl_wp_title_case' ) ) {
	/**
	 * Theme-author helper to apply title-case to an arbitrary string.
	 *
	 * @param string|null $options Title string to transform.
	 * @return string|null Transformed title (or null if nothing was passed).
	 */
	function thisismyurl_wp_title_case( $options = null ) {

		global $thisismyurl_WPTitleCase;

		if ( ! $thisismyurl_WPTitleCase instanceof thisismyurl_WPTitleCase ) {
			return $options;
		}

		return $thisismyurl_WPTitleCase->title_case( $options );
	}
}
