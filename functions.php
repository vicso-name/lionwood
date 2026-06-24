<?php

/**
 * lionwood functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package lionwood
*/

define("THEME_URI", get_template_directory_uri());
define("THEME_DIR", get_template_directory());
const THEME_NAME = 'lionwood';
const S_VERSION = "1.0.0";

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function lionwood_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on lionwood, use a find and replace
		* to change 'lionwood' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'lionwood', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(['primary' => __('Primary Menu', 'lionwood')]);


	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'lionwood_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'lionwood_setup' );


require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/acf_blocks.php';
require_once get_template_directory() . '/inc/testimonials-cpt.php';
require_once get_template_directory() . '/inc/industries-cpt.php';
require_once get_template_directory() . '/inc/industry_template_manager.php';
require_once get_template_directory() . '/inc/options-pages.php';
require_once get_template_directory() . '/inc/cases-cpt.php';
require_once get_template_directory() . '/inc/case_template_manager.php';
require_once get_template_directory() . '/inc/services-cpt.php';
require_once get_template_directory() . '/inc/csg-load-more.php';
require_once get_template_directory() . '/inc/ccg_ajax.php';
require_once get_template_directory() . '/inc/ig_ajax.php';
require_once get_template_directory() . '/inc/sg_ajax.php';
require_once get_template_directory() . '/inc/cig-load-more.php';
require_once get_template_directory() . '/inc/theme_function.php';
require_once get_template_directory() . '/inc/theme_settings.php';
require_once get_template_directory() . '/inc/acf-ai-fields.php';
require_once get_template_directory() . '/inc/acf-header-fields.php';
require_once get_template_directory() . '/inc/acf-error-page-fields.php';
require_once get_template_directory() . '/inc/acf-solutions-grid-fields.php';
require_once get_template_directory() . '/inc/acf-solution-fields.php';
require_once get_template_directory() . '/inc/service_template_manager.php';
require_once get_template_directory() . '/inc/subservices-cpt.php';
require_once get_template_directory() . '/inc/subservice_template_manager.php';
require_once get_template_directory() . '/inc/solutions-cpt.php';
require_once get_template_directory() . '/inc/careers-cpt.php';
require_once get_template_directory() . '/inc/products-cpt.php';
require_once get_template_directory() . '/inc/news-cpt.php';
require_once get_template_directory() . '/inc/whitepapers-cpt.php';
require_once get_template_directory() . '/inc/whitepaper_template_manager.php';
require_once get_template_directory() . '/inc/career_template_manager.php';
require_once get_template_directory() . '/inc/class-mega-menu-walker.php';
require_once get_template_directory() . '/inc/class-mobile-menu-walker.php';
