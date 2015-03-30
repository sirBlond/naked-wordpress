<?php
  /*-----------------------------------------------------------------------------------*/
  /* This file will be referenced every time a template/page loads on your Wordpress site
  /* This is the place to define custom fxns and specialty code
  /*-----------------------------------------------------------------------------------*/

// Define the version so we can easily replace it throughout the theme
define('NAKED_VERSION', 1.0);

/**
 * Register Theme Features:
 * Automatic Feed Links, Post Formats, Featured Images,
 * HTML5 Markup, Title tag, Translation.
 */
if (!function_exists('naked_theme_features')) {
  add_action('after_setup_theme', 'naked_theme_features');
  function naked_theme_features() {

    $theme_features = array(
      'automatic-feed-links',
      'post-formats' => array(
        'title' => 'post-formats',
        'args' => array(
          'status',
          'gallery',
          'image',
          'video',
          'audio'
        )
      ),
      'post-thumbnails',
      'html5' => array(
        'title' => 'html5',
        'args' => array(
          'search-form',
          'comment-form',
          'comment-list',
          'gallery'
        )
      ),
      'title-tag'
    );

    foreach($theme_features as $feature) {
      if(is_array($feature)){
        add_theme_support($feature['title'], $feature['args']);
      } else {
        add_theme_support($feature);
      }
    }

    load_theme_textdomain('naked', get_template_directory().'/langs');
  }
}

/*
 * Remove WP generator meta tag.
 */
add_filter('the_generator', 'naked_remove_wp_version');
function naked_remove_wp_version() {
  return '';
}

/**
 * Register Main menu.
 */
register_nav_menus( 
  array(
    'primary' =>  __( 'Primary Menu', 'naked' ), // Register the Primary menu
    // Copy and paste the line above right here if you want to make another menu, 
    // just change the 'primary' to another name
  )
);

/**
 * Activate sidebar.
 */
function naked_register_sidebars() {
  register_sidebar(array(       // Start a series of sidebars to register
    'id' => 'sidebar',          // Make an ID
    'name' => 'Sidebar',        // Name it
    'description' => 'Take it on the side...', // Dumb description for the admin side
    'before_widget' => '<div>', // What to display before each widget
    'after_widget' => '</div>', // What to display following each widget
    'before_title' => '<h3 class="side-title">',  // What to display before each widget's title
    'after_title' => '</h3>',   // What to display following each widget's title
    'empty_title'=> '',         // What to display in the case of no title defined for a widget
    // Copy and paste the lines above right here if you want to make another sidebar, 
    // just change the values of id and name to another word/name
  ));
} 
// adding sidebars to Wordpress (these are created in functions.php)
add_action( 'widgets_init', 'naked_register_sidebars' );

/**
 * Enqueue theme styles and scripts.
 */
add_action('wp_enqueue_scripts', 'naked_scripts');
function naked_scripts() {
  wp_enqueue_style('naked-style', get_template_directory_uri().'/style.css', '10000', 'all');
  wp_enqueue_script('naked-fitvid', get_template_directory_uri().'/js/jquery.fitvids.js', array('jquery'), NAKED_VERSION, true );

  // add theme scripts
  wp_enqueue_script('naked', get_template_directory_uri() . '/js/theme.min.js', array(), NAKED_VERSION, true );
}

/**
 * Login page customisations.
 */

// include stylesheet for wp-login.php
add_action('login_enqueue_scripts', 'naked_login_stylesheet');
function naked_login_stylesheet() {
  wp_register_style('login-styles', get_template_directory_uri().'/styles/login.css');
  wp_enqueue_style('login-styles');
}

// changing link from wordpress.org to website homepage address.
add_filter('login_headerurl', 'tprs_login_logo_url');
function tprs_login_logo_url() {
  return home_url();
}

// changing default title to the blog name.
add_filter('login_headertitle', 'tprs_login_logo_url_title');
function tprs_login_logo_url_title() {
  return get_bloginfo('name');
}

/**
 * Dashboard customisations.
 */

// removing "About Wordpress" from admin bar.
add_action( 'admin_bar_menu', 'naked_remove_wp_logo', 999 );
function naked_remove_wp_logo($wp_admin_bar) {
  $wp_admin_bar->remove_node('wp-logo');
}

// removing "Wordpress News" dashboard widget.
add_action('wp_dashboard_setup', 'naked_remove_dashboard_widget');
function naked_remove_dashboard_widget() {
  remove_meta_box('dashboard_primary', 'dashboard', 'side');
} 
