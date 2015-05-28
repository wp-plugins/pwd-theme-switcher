<?php

class PWD_THEME_SWITCHER_Plugin {

	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 1000 );
		add_action( 'init', array( __CLASS__, 'init' ) );

		if ( isset( $_COOKIE["switchtheme"] ) ) {
			add_filter( 'current_theme', array( __CLASS__, 'current_theme' ) );
			add_filter( 'template', array( __CLASS__, 'template' ) );
			add_filter( 'stylesheet', array( __CLASS__, 'stylesheet' ) );
		}
	}

	/**
	 *
	 * Set cookie for change theme
	 *
	 * @return bool
	 * @author Nicolas Kulka
	 */
	public static function init() {
		if ( isset( $_GET['action'] ) && 'switch' == $_GET['action'] && isset( $_GET['theme'] ) ) {
			if ( isset( $_COOKIE['switchtheme'] ) && ! empty( $_COOKIE['switchtheme'] ) && $_COOKIE['switchtheme'] == $_GET['theme'] ) {
				return false;
			}
			setcookie( 'switchtheme', $_GET['theme'], 0, '/' );

			// If preview post, regenerate url preview for redrect after switch theme
			$url_preview = '';
			if ( isset( $_GET['p'] ) && isset( $_GET['preview'] ) ) {
				$url_preview = '/?p=' . $_GET['p'] . '&preview=' . $_GET['preview'];
			}

			$redirect_url = ( isset( $_SERVER['REDIRECT_URL'] ) ) ? $_SERVER['REDIRECT_URL'] : '';
			wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . $redirect_url . $url_preview );
			exit;
		}
	}

	/**
	 *
	 * Add theme switcher in admin bar
	 *
	 * @author Nicolas Kulka
	 */
	public static function admin_bar_menu() {
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'id' => 'theme-switcher',
			'title' => apply_filters( 'theme_switcher_title', __( 'Theme Switcher', 'pwd-theme-switcher' ) ),
			'meta' => array( 'class' => 'theme-switcher' ),
		) );

		$themes = wp_get_themes();

		// Add filter for remove theme to theme switcher
		$themes = apply_filters( 'remove_theme_theme_switcher', $themes );

		foreach ( $themes as $key => $value ) {
			$theme = wp_get_theme( $key );

			$wp_admin_bar->add_menu( array(
				'parent' => 'theme-switcher',
				'title'  => $theme->get( 'Name' ),
				'id'     => $key,
				'href'   => add_query_arg( array(
					'action' => 'switch',
					'theme'  => $key,
				), 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] )
			) );
		}
	}

	/**
	 *
	 * Change current theme temporarly
	 *
	 * @param $current
	 *
	 * @return mixed
	 * @author Nicolas Kulka
	 */
	public static function current_theme( $current ) {
		if ( isset( $_COOKIE['switchtheme'] ) ) {
			$current = $_COOKIE['switchtheme'];
		}

		return $current;
	}

	/**
	 *
	 * Change template temporarly
	 *
	 * @param $template
	 *
	 * @return mixed
	 * @author Nicolas Kulka
	 */
	public static function template( $template ) {
		if ( isset( $_COOKIE['switchtheme'] ) ) {
			$theme = wp_get_theme( $_COOKIE['switchtheme'] );
			if ( '' != ( $theme->get( 'Template' ) ) ) {
				$template = $theme->get( 'Template' );
			} else {
				$template = $_COOKIE['switchtheme'];
			}
		}

		return $template;
	}

	/**
	 *
	 * Change stylesheet temporarly
	 *
	 * @param $stylesheet
	 *
	 * @return mixed
	 * @author Nicolas Kulka
	 */
	public static function stylesheet( $stylesheet ) {
		if ( isset( $_COOKIE['switchtheme'] ) ) {
			$stylesheet = $_COOKIE['switchtheme'];
		}

		return $stylesheet;
	}

}