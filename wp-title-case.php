<?php
/*
Plugin Name: WP Title Case
Plugin URI: http://thisismyurl.com/downloads/wp-title-case/
Description: Automatically applied title case rules to WordPress titles. This plugin automatically updates Page and Post titles to follow title casing rules.
Author: Christopher Ross
Author URI: http://thisismyurl.com/downloads/
Version: 15.01.01

*/


/**
 *
 * WP Title Case core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/wp-title-case/
 *
 * @package 	WP Title Case
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		WP Title Case 15.01
 *
 *
 */

/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_WPTC_NAME', 'WP Title Case' );
define( 'THISISMYURL_WPTC_SHORTNAME', 'WP Title Case' );

define( 'THISISMYURL_WPTC_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_WPTC_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_WPTC_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_WPTC_NAMESPACE', basename( THISISMYURL_WPTC_FILENAME, '.php' ) );
define( 'THISISMYURL_WPTC_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_WPTC_NAMESPACE ) );

define( 'THISISMYURL_WPTC_VERSION', '15.01' );

include_once( 'thisismyurl-common.php' );

/**
 * Creates the class required for WP Title Case
 *
 * @author     Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @see        wp_enqueue_scripts()
 * @since      Class available since Release 14.11
 *
 */
if( ! class_exists( 'thissimyurl_WPTitleCase' ) ) {
class thissimyurl_WPTitleCase extends thisismyurl_Common_WPTC {
	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		
		add_filter( 'the_title', array( $this, 'title_case' ), 999 );
		add_filter( 'get_the_title', array( $this, 'title_case' ), 999 );
     	add_filter( 'the_title_rss', array( $this, 'title_case' ), 999 );
			
	}
	
	
	
	/**
	  * changes the title case based on the settings above
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/get_option
	  * @since Method available since Release 15.01
	  *
	  */
	function title_case( $text_to_alter = NULL ) {

		/* set the current text */
		$result_text = $text_to_alter;

		/* if there is text to test */
		if ( $result_text ) {


			/* fetch the current options */
			$ignore_words = get_option( 'thisismyurl_wp_title_case_ignore_words' );
			$min_word_length = get_option( 'thisismyurl_wp_title_case_min_word_length' );
			$ignore_words_list = array();

			/* setup the options list */
			if( isset( $ignore_words ) ) {
				$ignored_word_array = explode( ',', trim( $ignore_words ) );

				if( isset( $ignored_word_array ) ) {
					foreach ( $ignored_word_array as $ignored_word_item ) {
						$ignore_words_list[] = strtolower( trim( $ignored_word_item ) );
					}
				}

			}

			$altered_text_array = explode( ' ', $result_text );
			foreach ( $altered_text_array as $word_to_test ) {

				if ( ! in_array( strtolower( $word_to_test ), $ignore_words_list ) && strlen( $word_to_test ) >= $min_word_length )
					$result_text_array[] = ucfirst( $word_to_test );
				else
					$result_text_array[] = $word_to_test;

			}

			$result_text = implode( ' ', $result_text_array );
		}
		return $result_text;

	}

	  
	
}
}

global $thissimyurl_WPTitleCase;

$thissimyurl_WPTitleCase = new thissimyurl_WPTitleCase;

$thissimyurl_WPTitleCase->run();




/**
  * Allows theme authors to call 
  *
  * @access public
  * @static
  * @uses $thissimyurl_WPTitleCase->title_case
  * @since Method available since Release 15.01
  *
  * @param  array see $thissimyurl_WPTitleCase->title_case_defaults() for accepted options
  *
  */
if ( ! function_exists( 'thisismyurl_wp_title_case' ) ) {
function thisismyurl_wp_title_case( $options = NULL ) {
	
	$thissimyurl_WPTitleCase->title_case( $options );

}
}