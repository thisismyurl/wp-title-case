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
	class thisismyurl_WPTitleCase {

		/**
		 * Constructor.
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'plugin_action_links_' . THISISMYURL_WPTC_FILENAME, array( $this, 'add_action_link' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'add_meta_boxes', array( $this, 'register_post_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_post_meta_box' ) );
		}

		/**
		 * Load plugin translations.
		 */
		public function load_textdomain() {

			load_plugin_textdomain(
				'wp-title-case',
				false,
				THISISMYURL_WPTC_FILEPATH . '/langs'
			);
		}

		/**
		 * Front-end style enqueue.
		 */
		public function enqueue_style() {

			if ( file_exists( __DIR__ . '/css/' . THISISMYURL_WPTC_NAMESPACE . '.css' ) ) {

				wp_enqueue_style(
					THISISMYURL_WPTC_NAMESPACE,
					THISISMYURL_WPTC_FILEPATHURL . 'css/' . THISISMYURL_WPTC_NAMESPACE . '.css',
					false,
					THISISMYURL_WPTC_VERSION
				);

			}
		}

		/**
		 * Admin style enqueue.
		 */
		public function admin_enqueue_scripts() {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen-id check, no state change.
			if ( ! isset( $_GET['page'] ) ) {
				return;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen-id check, no state change.
			$page = sanitize_key( wp_unslash( $_GET['page'] ) );

			if ( 'wp_title_case_settings_page' !== $page ) {
				return;
			}

			wp_register_style(
				'thisismyurl-common',
				THISISMYURL_WPTC_FILEPATHURL . 'css/thisismyurl-common.css',
				false,
				THISISMYURL_WPTC_VERSION
			);

			wp_enqueue_style( 'thisismyurl-common' );

			if ( file_exists( __DIR__ . '/css/' . THISISMYURL_WPTC_NAMESPACE . '-admin.css' ) ) {

				wp_enqueue_style(
					THISISMYURL_WPTC_NAMESPACE . '-admin',
					THISISMYURL_WPTC_FILEPATHURL . 'css/' . THISISMYURL_WPTC_NAMESPACE . '-admin.css',
					false,
					THISISMYURL_WPTC_VERSION
				);

			}
		}

		/**
		 * Register settings page.
		 */
		public function admin_menu() {

			add_options_page(
				THISISMYURL_WPTC_SHORTNAME,
				THISISMYURL_WPTC_SHORTNAME,
				'manage_options',
				'wp_title_case_settings_page',
				array( $this, 'settings_page' )
			);
		}

		/**
		 * Add settings action link.
		 *
		 * @param array  $links Action links.
		 * @param string $file Plugin file basename.
		 * @return array
		 */
		public function add_action_link( $links, $file ) {

			static $this_plugin;

			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}

			if ( dirname( $file ) === dirname( $this_plugin ) ) {
				$links[] = sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'options-general.php?page=wp_title_case_settings_page' ) ),
					esc_html__( 'Settings', 'wp-title-case' )
				);
			}

			return $links;
		}

		/**
		 * Render settings page.
		 */
		public function settings_page() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			?>
			<div id="thisismyurl-settings" class="wrap">
				<div class="thisismyurl-icon32"><br /></div>
				<h2><?php echo esc_html( THISISMYURL_WPTC_NAME ); ?></h2>

				<form method="POST" action="options.php">
					<?php
					settings_fields( 'thisismyurl_wp_title_case' );
					do_settings_sections( 'thisismyurl_wp_title_case' );
					submit_button();
					?>
				</form>
			</div>

			<?php $this->render_support_block(); ?>

			<div class="clear"></div>
			<?php
		}

		/**
		 * Render support block.
		 */
		protected function render_support_block() {

			?>
			<div id="wptc-support" style="margin-top:2em;">

				<h3><?php esc_html_e( 'How to support the software', 'wp-title-case' ); ?></h3>

				<p><?php esc_html_e( 'This plugin is free and open source. If it saves you time, here is how you can help:', 'wp-title-case' ); ?></p>

				<ul>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/' . THISISMYURL_WPTC_NAMESPACE . '/' ); ?>" rel="noopener noreferrer" target="_blank">
							<?php esc_html_e( 'Leave a review on WordPress.org', 'wp-title-case' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/' . THISISMYURL_WPTC_NAMESPACE ); ?>" rel="noopener noreferrer" target="_blank">
							<?php esc_html_e( 'Help others in the support forums', 'wp-title-case' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://github.com/thisismyurl/' . THISISMYURL_WPTC_NAMESPACE . '/issues' ); ?>" rel="noopener noreferrer" target="_blank">
							<?php esc_html_e( 'Report an issue or suggest a feature', 'wp-title-case' ); ?>
						</a>
					</li>
					<li>
						<a href="https://translate.wordpress.org/" rel="noopener noreferrer" target="_blank">
							<?php esc_html_e( 'Translate the plugin into your language', 'wp-title-case' ); ?>
						</a>
					</li>
					<li>
						<a href="https://www.paypal.com/donate/?business=info%40thisismyurl.com&item_name=WP+Title+Case&currency_code=USD" rel="noopener noreferrer" target="_blank">
							<?php esc_html_e( 'Donate via PayPal', 'wp-title-case' ); ?>
						</a>
					</li>
				</ul>

				<p>
					&#8212;&nbsp;<a href="https://thisismyurl.com/" rel="noopener noreferrer" target="_blank"><?php echo esc_html__( 'Christopher Ross', 'wp-title-case' ); ?></a>
				</p>

			</div>
			<?php
		}

		/**
		 * Register settings.
		 */
		public function admin_init() {

			add_settings_section(
				'thisismyurl_wp_title_case_general',
				esc_html__( 'General settings', 'wp-title-case' ),
				'__return_false',
				'thisismyurl_wp_title_case'
			);

			add_settings_field(
				'thisismyurl_wp_title_case_min_word_length',
				esc_html__( 'Minimum Word Length', 'wp-title-case' ),
				array( $this, 'thisismyurl_wp_title_case_general_min_word' ),
				'thisismyurl_wp_title_case',
				'thisismyurl_wp_title_case_general'
			);

			add_settings_field(
				'thisismyurl_wp_title_case_ignore_words',
				esc_html__( 'Ignored Words', 'wp-title-case' ),
				array( $this, 'thisismyurl_wp_title_case_general_ignore_words' ),
				'thisismyurl_wp_title_case',
				'thisismyurl_wp_title_case_general'
			);

			register_setting(
				'thisismyurl_wp_title_case',
				'thisismyurl_wp_title_case_min_word_length',
				array(
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
					'default'           => 3,
				)
			);

			register_setting(
				'thisismyurl_wp_title_case',
				'thisismyurl_wp_title_case_ignore_words',
				array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '',
				)
			);

			$old_options = get_option( 'thisismyurl_title_case' );

			if ( ! empty( $old_options ) ) {

				if ( isset( $old_options['text_too_short_to_process'] ) && ! empty( $old_options['text_too_short_to_process'] ) ) {
					update_option( 'thisismyurl_wp_title_case_min_word_length', (int) $old_options['text_too_short_to_process'] );
				}

				if ( isset( $old_options['ignore_words'] ) && ! empty( $old_options['ignore_words'] ) ) {
					update_option( 'thisismyurl_wp_title_case_ignore_words', sanitize_text_field( $old_options['ignore_words'] ) );
				}

				delete_option( 'thisismyurl_title_case' );
			}
		}

		/**
		 * Render minimum-word-length select.
		 */
		public function thisismyurl_wp_title_case_general_min_word() {

			$min_word_length = (int) get_option( 'thisismyurl_wp_title_case_min_word_length', 3 );

			if ( $min_word_length < 1 ) {
				$min_word_length = 3;
			}
			?>
			<select name="thisismyurl_wp_title_case_min_word_length" id="thisismyurl_wp_title_case_min_word_length">
				<?php for ( $word_length = 1; $word_length < 8; $word_length++ ) : ?>
					<option value="<?php echo esc_attr( (string) $word_length ); ?>" <?php selected( $min_word_length, $word_length ); ?>>
						<?php echo esc_html( (string) $word_length ); ?>
					</option>
				<?php endfor; ?>
			</select>
			<?php
		}

		/**
		 * Render ignore-words textarea.
		 */
		public function thisismyurl_wp_title_case_general_ignore_words() {

			$ignore_words = (string) get_option( 'thisismyurl_wp_title_case_ignore_words', '' );
			?>
			<textarea id="thisismyurl_wp_title_case_ignore_words" name="thisismyurl_wp_title_case_ignore_words" rows="5" cols="50"><?php echo esc_textarea( $ignore_words ); ?></textarea>
			<?php
		}

		/**
		 * Register per-post skip meta box.
		 *
		 * @param string $post_type Post type slug.
		 */
		public function register_post_meta_box( $post_type ) {

			$post_types = (array) apply_filters(
				'thisismyurl_wp_title_case_meta_box_post_types',
				array( 'post', 'page' )
			);

			if ( ! in_array( $post_type, $post_types, true ) ) {
				return;
			}

			add_meta_box(
				'wptc_skip_meta_box',
				esc_html__( 'WP Title Case', 'wp-title-case' ),
				array( $this, 'render_post_meta_box' ),
				$post_type,
				'side',
				'low'
			);
		}

		/**
		 * Render per-post skip meta box.
		 *
		 * @param WP_Post $post Current post.
		 */
		public function render_post_meta_box( $post ) {

			$skip = (bool) get_post_meta( $post->ID, '_wptc_skip', true );

			wp_nonce_field( 'wptc_skip_meta_box', 'wptc_skip_meta_box_nonce' );
			?>
			<p>
				<label>
					<input type="checkbox" name="wptc_skip" value="1" <?php checked( $skip ); ?> />
					<?php esc_html_e( 'Skip title-case transform on this post.', 'wp-title-case' ); ?>
				</label>
			</p>
			<p class="description">
				<?php esc_html_e( 'Use this when the title contains intentional casing (e.g. iPhone, ALL CAPS, brand names).', 'wp-title-case' ); ?>
			</p>
			<?php
		}

		/**
		 * Save per-post skip flag.
		 *
		 * @param int $post_id Post ID.
		 */
		public function save_post_meta_box( $post_id ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['wptc_skip_meta_box_nonce'] ) ) {
				return;
			}

			$nonce = sanitize_text_field( wp_unslash( $_POST['wptc_skip_meta_box_nonce'] ) );

			if ( ! wp_verify_nonce( $nonce, 'wptc_skip_meta_box' ) ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( ! empty( $_POST['wptc_skip'] ) ) {
				update_post_meta( $post_id, '_wptc_skip', 1 );
			} else {
				delete_post_meta( $post_id, '_wptc_skip' );
			}
		}

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
