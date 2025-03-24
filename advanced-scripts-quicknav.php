<?php
/*
Plugin Name:  Advanced Scripts QuickNav
Plugin URI:   https://github.com/deckerweb/advanced-scripts-quicknav
Description:  For Script and Code Snippets enthusiasts: Adds a quick-access navigator (aka QuickNav) to the WordPress Admin Bar (Toolbar). It allows easy access to your Scripts/ Code Snippets listed by Active, Inactive or Folder group. Safe Mode is supported. Comes with inspiring links to snippet libraries.
Project:      Code Snippet: DDW Advanced Scripts QuickNav
Version:      1.0.0
Author:       David Decker – DECKERWEB
Author URI:   https://deckerweb.de/
Text Domain:  advanced-scripts-quicknav
Domain Path:  /languages/
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Requires WP:  6.7
Requires PHP: 7.4
Copyright:    © 2025, David Decker – DECKERWEB

Original plugin/company logo icon, Copyright: © Clean Plugins by Abdelouahed Errouaguy
All Other Icons, Copyright: © Remix Icon
	
TESTED WITH:
Product				Versions
--------------------------------------------------------------------------------------------------------------
PHP 				8.0, 8.3
WordPress			6.7.2 ... 6.8 Beta
Advanced Scripts	2.5.2
--------------------------------------------------------------------------------------------------------------

VERSION HISTORY:
Date		Version		Description
--------------------------------------------------------------------------------------------------------------
2025-03-24	1.0.0		Initial release
2025-03-23	0.5.0		Internal test version
2025-03-23	0.0.0		Development start
--------------------------------------------------------------------------------------------------------------
*/

/** Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly.
}

if ( ! class_exists( 'DDW_Advanced_Scripts_QuickNav' ) ) :

class DDW_Advanced_Scripts_QuickNav {

	/** Class constants & variables */
	private const VERSION = '1.0.0';
	private const DEFAULT_MENU_POSITION	= 999;  // default: 999
		
	private static $scripts_all      = 0;
	private static $scripts_active   = 0;
	private static $scripts_inactive = 0;
	
	public static $expert_mode = TRUE;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_bar_menu',              array( $this, 'add_admin_bar_menu' ), self::DEFAULT_MENU_POSITION );
		add_action( 'admin_enqueue_scripts',       array( $this, 'enqueue_admin_bar_styles' ) );  // for Admin
		add_action( 'wp_enqueue_scripts',          array( $this, 'enqueue_admin_bar_styles' ) );  // for front-end
		add_action( 'enqueue_block_editor_assets', array( $this, 'adminbar_block_editor_fullscreen' ) );  // for Block Editor
		add_filter( 'debug_information',           array( $this, 'site_health_debug_info' ), 9 );
	}
	
	/**
	 * Is expert mode active?
	 *   Gives some more stuff that is more focused at (plugin/snippet) developers
	 *   and mostly not needed for fast site building.
	 *
	 * @return bool
	 */
	private function is_expert_mode(): bool {
		
		self::$expert_mode = ( defined( 'ASQN_EXPERT_MODE' ) ) ? (bool) ASQN_EXPERT_MODE : self::$expert_mode;
			
		return self::$expert_mode;
	}
	
	/**
	 * Get specific Admin Color scheme colors we need. Covers all 9 default
	 *	 color schemes coming with a default WordPress install.
	 *   (helper function)
	 */
	private function get_scheme_colors() {
		
		$scheme_colors = array(
			'fresh' => array(
				'bg'    => '#1d2327',
				'base'  => 'rgba(240,246,252,.6)',
				'hover' => '#72aee6',
			),
			'light' => array(
				'bg'    => '#e5e5e5',
				'base'  => '#999',
				'hover' => '#04a4cc',
			),
			'modern' => array(
				'bg'    => '#1e1e1e',
				'base'  => '#f3f1f1',
				'hover' => '#33f078',
			),
			'blue' => array(
				'bg'    => '#52accc',
				'base'  => '#e5f8ff',
				'hover' => '#fff',
			),
			'coffee' => array(
				'bg'    => '#59524c',
				'base'  => 'hsl(27.6923076923,7%,95%)',
				'hover' => '#c7a589',
			),
			'ectoplasm' => array(
				'bg'    => '#523f6d',
				'base'  => '#ece6f6',
				'hover' => '#a3b745',
			),
			'midnight' => array(
				'bg'    => '#363b3f',
				'base'  => 'hsl(206.6666666667,7%,95%)',
				'hover' => '#e14d43',
			),
			'ocean' => array(
				'bg'    => '#738e96',
				'base'  => '#f2fcff',
				'hover' => '#9ebaa0',
			),
			'sunrise' => array(
				'bg'    => '#cf4944',
				'base'  => 'hsl(2.1582733813,7%,95%)',
				'hover' => 'rgb(247.3869565217,227.0108695652,211.1130434783)',
			),
		);
		
		/** No filter currently b/c of sanitizing issues with the above CSS values */
		//$scheme_colors = (array) apply_filters( 'ddw/quicknav/asqn_scheme_colors', $scheme_colors );
		
		return $scheme_colors;
	}
	
	/**
	 * Enqueue custom styles for the Admin Bar.
	 *   NOTE: Used within Admin and on the front-end (if Toolbar enabled).
	 */
	public function enqueue_admin_bar_styles() {
		
		/**
		 * Depending on user color scheme get proper base and hover color values for the main item (svg) icon.
		 */
		$user_color_scheme = get_user_option( 'admin_color' );
		$admin_scheme      = $this->get_scheme_colors();
		
		$base_color  = $admin_scheme[ $user_color_scheme ][ 'base' ];
		$hover_color = $admin_scheme[ $user_color_scheme ][ 'hover' ];
		
		/**
		 * Build the inline CSS
		 *   NOTE: We need to use 'sprintf()' because of the percentage values and similar!
		 */
		$inline_css = sprintf(
			'
				#wpadminbar .asqn-scripts-list .ab-sub-wrapper ul li span.location,
				#wpadminbar .asqn-scripts-list .ab-sub-wrapper ul li span.status {
				font-family: monospace;
				font-size: %1$s;
				vertical-align: super;
				}
				
				#wpadminbar .asqn-scripts-list .ab-sub-wrapper ul li span.location,
				#wpadminbar .asqn-scripts-list .ab-sub-wrapper ul li span.status.inactive {
				/* filter: brightness(%2$s); */
				color: hsl(0, %3$s, %4$s);
				}
				
				#wpadminbar .asqn-scripts-list .ab-sub-wrapper ul li span.status.active {
				/* filter: brightness(%2$s); */
				color: hsl(120, %3$s, %7$s);
				}
				
				#wpadminbar .asqn-safemode {
					background-color: #9C1005;
				}
				#wpadminbar .asqn-safemode:hover,
				#wpadminbar ul li.asqn-safemode:hover {
					background: #BD3126;
				}
				#wpadminbar .asqn-safemode a {
					color: #FBE4C6;
					font-weight: 700;
				}
				
				#wpadminbar .has-icon .icon-svg svg {
					display: inline-block;
					margin-bottom: 3px;
					vertical-align: middle;
					width: 16px;
					height: 16px;
				}
				
				.icon-svg.ab-icon svg {
					width: 15px;
					height: 15px;
				}
				
				.asqn-scripts-list .ab-item .icon-svg.ab-icon svg {
					color: %5$s;
				}
				
				.asqn-scripts-list .ab-item:hover .icon-svg.ab-icon svg {
					color: %6$s;  /* inherit; */
				}								
			',
			'80%',			// 1
			'120%',			// 2
			'100%',			// 3
			'70%',			// 4
			$base_color,	// 5
			$hover_color,	// 6
			'40%'			// 7
		);
		
		/** Only add the styles if Admin Bar is showing */
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'admin-bar', $inline_css );
		}
	}

	/**
	 * Check for active SCRIPT_DEBUG constant. (helper function)
	 *
	 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/#script_debug
	 */
	private function is_wp_dev_mode_active() {
		
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}
	
	/**
	 * Check for active Safe Mode constant. (helper function)
	 *
	 * @link https://www.cleanplugins.com/blog/advanced-scripts-2-4-0-release-overview/
	 */
	private function is_safe_mode_active() {
		
		if ( defined( 'AS_SAFE_MODE' ) && AS_SAFE_MODE ) return TRUE;
		
		return FALSE;
	}
	
	/**
	 * Get type of Script/ Snippet. (helper function)
	 *
	 * @return string Type/Language of script to execute.
	 */
	private function get_type( $scope ) {
		
		$type = '';
		
		switch ( $scope ) {
			case 'folder':
				$type = 'Folder';
				break;
		
			case 'application/x-httpd-php':
				$type = 'PHP';
				break;
		
			case 'url/javascript':
			case 'text/javascript':
				$type = 'JS';
				break;
		
			case 'url/css':
			case 'text/css':
				$type = 'CSS';
				break;
		
			case 'text/x-scss':
			case 'text/x-scss-partial':
				$type = 'SASS';
				break;
		
			case 'text/x-less':
				$type = 'Less';
				break;
		
			case 'text/html':
				$type = 'HTML';
				break;
		}
		
		return $type;
	}
	
	/**
	 * Get type of Script/ Snippet. (helper function)
	 *
	 * @return string SVG icon of script type (built-in versions).
	 */
	private function get_icon( $type ) {
		
		$type = $this->get_type( $type );
		
		switch ( strtolower( $type ) ) {
			case 'php':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-php"><path fill="#7e57c2" d="M0 0h18v18H0z"></path><path d="M4.805 8.236c.438 0 .726.08.875.24s.18.438.105.832c-.08.407-.232.7-.46.875s-.573.263-1.037.263h-.7l.43-2.2h.788zM2 12.687h1.15l.27-1.405h.985c.433 0 .792-.044 1.072-.136s.538-.245.766-.46a2.36 2.36 0 0 0 .468-.586 2.36 2.36 0 0 0 .254-.709c.123-.626.03-1.116-.276-1.462s-.792-.525-1.457-.525H3.02L2 12.687zM7.816 6h1.142l-.27 1.405h1.015c.64 0 1.08.114 1.326.337s.315.586.22 1.085l-.477 2.46H9.6l.455-2.337c.053-.267.03-.446-.057-.543s-.28-.144-.573-.144H8.52l-.586 3.024H6.792L7.816 6zm5.97 2.236c.438 0 .726.08.875.24s.18.438.105.832c-.08.407-.232.7-.46.875s-.573.263-1.037.263h-.7l.43-2.2h.788zm-2.805 4.45h1.15l.27-1.405h.985c.433 0 .792-.044 1.072-.136s.534-.245.766-.46a2.36 2.36 0 0 0 .468-.586 2.36 2.36 0 0 0 .254-.709c.123-.626.03-1.116-.276-1.462s-.792-.525-1.457-.525H12l-1.02 5.282z" fill="#fff"></path></svg>';
				break;
		
			case 'css':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-css"><path fill="#2196f3" d="M0 0h18v18H0z"></path><path d="M9.215 3H4l.048.558.096 1.052.048.47h7.957l-.19 2.13h-5.23l.056.558.088 1.052.048.47h4.856l-.24 2.695-2.32.622v.008h-.008l-2.32-.63-.144-1.658h-2.09l.295 3.27 4.258 1.188h.016v-.008l4.258-1.18.024-.35.36-3.955L14.42 3H9.215z" fill="#fff"></path></svg>';
				break;
		
			case 'js':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-js"><path d="M0 0H18V18H0V0Z" fill="#f6df1c"></path><path d="M4 14.512l1.393-.91c.264.483.5.91 1.102.91s.9-.22.9-1.074V7.62H9.1v5.845a2.32 2.32 0 0 1-.656 1.901 2.32 2.32 0 0 1-1.894.676A2.65 2.65 0 0 1 4 14.512h0zm6.046-.182l1.393-.8c.163.306.408.56.707.735s.64.264.987.257c.7 0 1.156-.355 1.156-.838s-.464-.8-1.247-1.138l-.428-.182c-1.23-.528-2.048-1.184-2.048-2.577.001-.318.068-.632.198-.922s.318-.55.554-.764.513-.375.815-.475.62-.136.938-.106a2.54 2.54 0 0 1 2.394 1.375l-1.33.856c-.092-.206-.24-.38-.43-.504s-.4-.188-.635-.188c-.103-.01-.206.001-.304.033s-.188.084-.265.152-.14.152-.182.245-.066.195-.068.298c0 .52.32.728 1.056 1.047l.42.182c1.457.62 2.276 1.256 2.276 2.73s-1.21 2.385-2.832 2.385c-.634.04-1.266-.102-1.82-.412s-1.007-.774-1.303-1.336" fill="#010101"></path></svg>';
				break;
	
			case 'html':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-html"><path fill="#ff5722" d="M0 0h18v18H0z"></path><path d="M9.215 3.5H4l.048.566.5 5.733h7.216l-.247 2.695-2.32.63-2.32-.63-.144-1.658h-2.09l.287 3.27 4.266 1.18h.008l4.258-1.18.032-.35.542-6.036H6.464l-.19-2.13h7.965l.04-.47.096-1.052.048-.566H9.215z" fill="#fff"></path></svg>';
				break;
	
			case 'sass':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-sass"><path fill="#c69" d="M0 0h18v18H0z"></path><path d="M14.753 3.776c-.135-.54-.52-.992-1.105-1.308-1.15-.61-3-.63-4.69-.023-1.015.36-2.932 1.173-4.375 2.503-1.443 1.353-1.67 2.55-1.556 3.022.248 1.308 1.624 2.233 2.706 3l.767.564c-.61.316-2.255 1.24-2.706 2.2-.338.767-.18 1.33-.045 1.58.135.293.36.52.586.586s.474.1.722.1c.97 0 1.917-.52 2.503-1.398a3.29 3.29 0 0 0 .361-2.774c.383-.1.79-.1 1.24-.045.88.113 1.308.45 1.51.722.226.293.27.586.248.767-.045.338-.316.54-.45.63-.113.068-.203.135-.18.27.045.18.226.158.293.158.203-.045.925-.406.97-1.218.023-.496-.18-.992-.564-1.398-.496-.52-1.24-.79-2.097-.767-.63 0-1.06.068-1.398.18l-.023-.023c-.316-.338-.744-.654-1.128-.97-.902-.677-1.737-1.33-1.69-2.3.068-1.218 1.263-2.413 3.586-3.563 2.052-1.015 3.72-1.06 4.6-.744.36.135.61.316.7.52.18.383.113.88-.203 1.42-.52.902-1.827 2.03-3.834 2.255-1.218.135-1.737-.406-1.76-.43-.135-.158-.226-.248-.383-.158-.18.1-.1.338-.045.43.1.248.474.677 1.128.902.52.18 1.85.293 3.496-.338 1.894-.767 3.203-2.797 2.82-4.353zm-7.78 9.608c-.023.045-.023.1-.045.135s-.045.1-.045.135c-.1.203-.226.406-.406.586-.52.564-1.218.722-1.443.586-.068-.045-.1-.113-.113-.203-.045-.27.113-.812.586-1.286.564-.586 1.353-1.015 1.534-1.105.068.406.045.79-.068 1.15z" fill="#fff"></path></svg>';
				break;
		
			case 'less':
				$icon = '<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="none" class="asqn-icon-type asqn-icon-less"><path fill="#1d365d" d="M0 0h18v18H0z"></path><path d="M11.355 8.9c.845.296 1.288.802 1.288 1.54a1.37 1.37 0 0 1-.528 1.14c-.36.274-.845.443-1.5.443-.612 0-1.182-.17-1.7-.443-.063-.042-.106-.106-.106-.2.02-.127.063-.274.127-.422l.17-.338c.063-.085.148-.106.253-.063.465.2.845.338 1.204.338.17 0 .317-.02.422-.085s.148-.148.148-.232c0-.2-.148-.338-.443-.422l-.528-.2c-.802-.296-1.204-.76-1.204-1.436 0-.486.17-.866.507-1.14s.78-.528 1.35-.528a3.9 3.9 0 0 1 .93.127c.296.063.528.2.74.338.063.042.106.106.106.2a1.32 1.32 0 0 1-.106.465c-.063.148-.127.275-.2.36-.063.063-.148.084-.2.042-.486-.2-.887-.317-1.225-.317-.127 0-.232.02-.296.084s-.106.127-.106.2c0 .148.127.275.36.36l.57.17zm3.927.76c-.274.232-.422.57-.422 1.035v1.668c0 .55-.17.97-.55 1.267s-.824.422-1.33.443h-.296v-.697a.16.16 0 0 1 .127-.169c.2-.063.274-.148.36-.232.2-.17.338-.465.338-.845v-1.35c0-.465.042-.824.2-1.077.127-.2.338-.36.633-.528.127-.063.127-.253 0-.338-.36-.2-.6-.465-.74-.78-.106-.232-.106-.55-.106-.95V5.88c0-.4-.127-.697-.296-.845a.72.72 0 0 0-.38-.211c-.084-.02-.127-.106-.127-.2V4.2a.19.19 0 0 1 .19-.19h.57c.317 0 .6.17.823.36a1.63 1.63 0 0 1 .549.824c.063.2.063.38.063.57V7.18c0 .507.148.887.4 1.12.127.127.296.232.57.338.084.02.127.106.127.17v.36c0 .084-.042.148-.127.2a1.35 1.35 0 0 0-.59.317zM4.914 4H4.64c-.317 0-.6.17-.824.36-.253.2-.486.465-.55.824-.042.2-.02.38-.02.57V7.17c0 .507-.2.866-.443 1.12-.127.127-.38.232-.676.338-.084.042-.127.106-.127.2v.36c0 .084.042.148.127.2a2.17 2.17 0 0 1 .633.317 1.3 1.3 0 0 1 .465 1.035v1.668c0 .55.127.97.507 1.267s.824.422 1.33.443H5.2a.19.19 0 0 0 .19-.19v-.507a.16.16 0 0 0-.127-.169c-.2-.063-.317-.148-.4-.232-.2-.17-.296-.465-.296-.845V10.8c0-.465-.084-.824-.253-1.077-.127-.2-.338-.36-.633-.528-.127-.063-.127-.253 0-.338.36-.2.6-.465.74-.78.106-.232.148-.55.148-.95V5.9c0-.4.084-.697.253-.845.084-.084.232-.148.443-.2h.2a.19.19 0 0 0 .19-.19V4.2a.19.19 0 0 0-.19-.19L4.914 4zm3.42 6.736H8.08c-.275 0-.38-.148-.38-.443v-5.47c0-.338-.106-.57-.2-.697C7.363 4 7.152 4 6.857 4h-.465a.19.19 0 0 0-.19.19v6.377c0 .465.106.78.296 1.014.2.2.486.317.93.317a6.74 6.74 0 0 0 .887-.063.23.23 0 0 0 .169-.169v-.253a5.38 5.38 0 0 0-.148-.676z" fill="#f6f6f6"></path></svg>';
				break;
		}
		
		return '<span class="icon-svg">' . $icon . '</span> ';
	}
	
	/**
	 * Get location of Script/ Snippet. (helper function)
	 *
	 * @return string Location of script execution (lowercase).
	 */
	private function get_location( $where ) {
		
		switch ( $where ) {
			case 'all':
				$location = _x( 'everywhere', 'Script/ Snippet scope: everywhere (global)', 'advanced-scripts-quicknav' );
				break;
		
			case 'front':
				$location = _x( 'front-end', 'Script/ Snippet scope: front-end', 'advanced-scripts-quicknav' );
				break;
		
			default:
				$location = $where;
		}
		
		return esc_html( strtolower( $location ) );
	}
	
	/**
	 * Get number of all Scripts (without folders). (helper function)
	 *
	 * @return int Number of scripts.
	 */
	private function script_counter() {
		
		if ( ! function_exists( 'cpas_scripts_manager' ) ) return 0;
		
		/** Get all Scripts from the DB (official operator function) */
		$scripts = cpas_scripts_manager();
		$scripts = $scripts->scripts;
		
		$count_all = 0;
		
		/** First, iterate through all Scripts, use only Active ones! */
		if ( $scripts ) {
			
			foreach ( $scripts as $script ) {
				/** Get only scripts but no folders */
				if ( 'folder' === $script[ 'type' ] ) {
					continue;
				}
				$count_all++;
			}  // end foreach
		}  // end if
		
		/** Set active counter for class */
		self::$scripts_all = $count_all;
		
		return $count_all;
	}
	
	/**
	 * Adds the main Scripts (Code Snippets) menu and its submenus to the Admin Bar.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance.
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		
		/** Don't do anything if Advanced Scripts plugin is NOT active */
		if ( ! defined( 'EPXADVSC_VER' ) || ! function_exists( 'cpas_scripts_manager' ) ) {
			return;
		}
		
		/** Also, don't do anything if the current user has no permission */
		$asqn_permission = ( defined( 'ASQN_VIEW_CAPABILITY' ) && ! empty( ASQN_VIEW_CAPABILITY ) ) ? ASQN_VIEW_CAPABILITY : 'activate_plugins';
		
		if ( ! current_user_can( sanitize_key( $asqn_permission ) ) ) {
			return;
		}
		
		/** Build the main item title, optional scripts count value */
		$all_scripts = $this->script_counter();
		$counter     = ( defined( 'ASQN_COUNTER' ) && 'yes' === sanitize_key( ASQN_COUNTER ) ) ? ' (' . intval( $all_scripts ) . ')' : '';
		$asqn_name   = ( defined( 'ASQN_NAME_IN_ADMINBAR' ) ) ? esc_html( ASQN_NAME_IN_ADMINBAR ) : esc_html__( 'Scripts', 'advanced-scripts-quicknav' );
		$asqn_name   = $asqn_name . $counter;

		/** Default "script icon" */
		$code_icon = '<span class="icon-svg ab-icon"><svg width="100%" height="100%" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5;"><g transform="matrix(1.35922,0,0,1.48936,-179.165,-253.532)"><ellipse cx="508.5" cy="514" rx="360.5" ry="329" style="fill:none;stroke:white;stroke-width:29.22px;"/></g><g transform="matrix(27.9,0,0,27.9,177.2,177.2)"><path d="M4,18L4,14.3C4,13.472 3.328,12.8 2.5,12.8L2,12.8L2,11.2L2.5,11.2C3.328,11.2 4,10.528 4,9.7L4,6C4,4.343 5.343,3 7,3L8,3L8,5L7,5C6.448,5 6,5.448 6,6L6,10.1C6,10.986 5.424,11.737 4.626,12C5.424,12.263 6,13.014 6,13.9L6,18C6,18.552 6.448,19 7,19L8,19L8,21L7,21C5.343,21 4,19.657 4,18ZM20,14.3L20,18C20,19.657 18.657,21 17,21L16,21L16,19L17,19C17.552,19 18,18.552 18,18L18,13.9C18,13.014 18.576,12.263 19.374,12C18.576,11.737 18,10.986 18,10.1L18,6C18,5.448 17.552,5 17,5L16,5L16,3L17,3C18.657,3 20,4.343 20,6L20,9.7C20,10.528 20.672,11.2 21.5,11.2L22,11.2L22,12.8L21.5,12.8C20.672,12.8 20,13.472 20,14.3Z" style="fill:white;fill-rule:nonzero;"/></g></svg></span> ';
		
		/** Optional code icon by Remix Icon */
		$remix_icon = '<span class="icon-svg ab-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12L18.3431 17.6569L16.9289 16.2426L21.1716 12L16.9289 7.75736L18.3431 6.34315L24 12ZM2.82843 12L7.07107 16.2426L5.65685 17.6569L0 12L5.65685 6.34315L7.07107 7.75736L2.82843 12ZM9.78845 21H7.66009L14.2116 3H16.3399L9.78845 21Z"></path></svg></span> ';
		
		/** Original blue icon (svg-ed) by Clean Plugins */
		$blue_icon = '<span class="icon-svg ab-icon"><svg width="100%" height="100%" viewBox="0 0 300 300" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><use id="Hintergrund" xlink:href="#_Image1" x="0" y="0" width="300px" height="300px"/><defs><image id="_Image1" width="300px" height="300px" xlink:href="data:image/png;base64,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"/></defs></svg></span> ';
		
		/** "Fire" Emoji for Safe Mode */
		$safemode = ( $this->is_safe_mode_active() ) ? ' 🔥' : '';
			
		$title_html = $code_icon . '<span class="ab-label">' . $asqn_name . '</span>' . $safemode;
		
		if ( defined( 'ASQN_ICON' ) && 'blue' === sanitize_key( ASQN_ICON ) ) {
			$title_html = $blue_icon . '<span class="ab-label">' . $asqn_name . '</span>' . $safemode;
		} elseif ( defined( 'ASQN_ICON' ) && 'remix' === sanitize_key( ASQN_ICON ) ) {
			$title_html = $remix_icon . '<span class="ab-label">' . $asqn_name . '</span>' . $safemode;
		}
		
		/** Add the parent menu item with an icon (main node) */
		$wp_admin_bar->add_node( array(
			'id'    => 'ddw-advscripts-quicknav',
			'title' => $title_html,
			'href'  => esc_url( admin_url( 'tools.php?page=advanced-scripts' ) ),
			'meta'  => array( 'class' => 'asqn-scripts-list has-icon' ),
		) );
		
		/** Add submenus */
		$this->add_safemode_submenu( $wp_admin_bar );  // group node
		$this->add_scripts_by_status_group( $wp_admin_bar );  // group node
		$this->add_scripts_by_folder_group( $wp_admin_bar );  // group node
		$this->add_scripts_new_group( $wp_admin_bar );  // group node
		$this->add_settings_group( $wp_admin_bar );  // group node
		$this->add_library_group( $wp_admin_bar );  // group node
		$this->add_libraries_submenu( $wp_admin_bar );
		$this->add_footer_group( $wp_admin_bar );  // group node
		$this->add_links_submenu( $wp_admin_bar );
		$this->add_about_submenu( $wp_admin_bar );
	}

	/**
	 * Add group node for Safe Mode, and Script Debug
	 */
	private function add_safemode_submenu( $wp_admin_bar ) {
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-group-safemode',
			'parent' => 'ddw-advscripts-quicknav',
		) );
		
		/** Warning for Advanced Scripts' own Safe Mode */
		if ( $this->is_safe_mode_active() ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-safemode-active',
				'title'  => esc_html__( 'SAFE MODE is active 🔥', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts' ) ),
				'parent' => 'asqn-group-safemode',
				'meta'   => array( 'class' => 'asqn-safemode' ),
			) );
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-safemode-active-topright',
				'title'  => strtoupper( esc_html__( 'Advanced Scripts Safe Mode active 🔥', 'advanced-scripts-quicknav' ) ),
				'href'   => 'https://www.cleanplugins.com/blog/advanced-scripts-2-4-0-release-overview/',
				'parent' => 'top-secondary',	/** Puts the text on the right side of the Toolbar! */
				'meta'   => array( 'class' => 'asqn-safemode', 'target' => '_blank', 'rel' => 'nofollow noopener noreferrer' ),
			) );
		}  // end if
		
		/** Warning for WordPress' SCRIPT_DEBUG constant */
		if ( $this->is_wp_dev_mode_active() ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devmode-active',
				'title'  => esc_html__( 'SCRIPT_DEBUG is on ⚠', 'advanced-scripts-quicknav' ),
				'href'   => 'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/#script_debug',
				'parent' => 'asqn-group-safemode',
				'meta'   => array( 'class' => 'asqn-safemode', 'rel' => 'nofollow noopener noreferrer' ),
			) );
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devmode-active-topright',
				'title'  => strtoupper( esc_html__( 'SCRIPT_DEBUG is on ⚠', 'advanced-scripts-quicknav' ) ),
				'href'   => 'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/#script_debug',
				'parent' => 'top-secondary',	/** Puts the text on the right side of the Toolbar! */
				'meta'   => array( 'class' => 'asqn-safemode', 'target' => '_blank', 'rel' => 'nofollow noopener noreferrer' ),
			) );
		}  // end if
	}
					
	/**
	 * Add group node for Active & Inactive Scripts
	 */
	private function add_scripts_by_status_group( $wp_admin_bar ) {
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-group-status',
			'parent' => 'ddw-advscripts-quicknav',
		) );
		
		$this->add_scripts_to_admin_bar( $wp_admin_bar );
	}

	/**
	 * Status Group: Add Active & Inactive Scripts
	 */
	private function add_scripts_to_admin_bar( $wp_admin_bar ) {
		
		if ( ! function_exists( 'cpas_scripts_manager' ) ) return $wp_admin_bar;
		
		/** Get all Scripts from the DB (official operator function) */
		$scripts = cpas_scripts_manager();
		$scripts = $scripts->scripts;
		
		$count_active   = 0;
		$count_inactive = 0;
		
		$parent = '';
		
		/** First, iterate through all Scripts, use only Active ones! */
		if ( $scripts ) {
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-active',
				//'title'  => esc_html__( 'Active Scripts', 'advanced-scripts-quicknav' ) . ' (' . $count_active . ')',
				'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts' ) ),
				'parent' => 'asqn-group-status',
			) );
			
			foreach ( $scripts as $script ) {
				
				/** List only active scripts for now */
				if ( ! $script[ 'status' ] || 'folder' === $script[ 'type' ] ) {
					continue;
				}

				$count_active++;
				
				/** Consider parent folders */
				$parent = ( 0 !== $script[ 'parent' ] ) ? intval( $script[ 'parent' ] ) : '';
				$parent = ( ! empty( $parent ) ) ? '&parent=' . $parent : '';
	
				$edit_link = admin_url( 'tools.php?page=advanced-scripts' . $parent . '&edit=' . intval( $script[ 'term_id' ] ) );
				
				$icon = $this->get_icon( $script[ 'type' ] );
				
				$location = sprintf(
					' <span class="location">%s</span>',
					esc_html( $this->get_location( $script[ 'location' ] ) )
				);
				
				$wp_admin_bar->add_node( array(
					'id'     => 'asqn-script-' . intval( $script[ 'term_id' ] ),
					'title'  => $icon . esc_html( $script[ 'title' ] ) . $location,
					'href'   => esc_url( $edit_link ),
					'parent' => 'asqn-active',
				) );
				
			}  // end foreach
		}  // end if - active check
		
		/** Populate the "Active" title string with counter result after foreach iteration */
		$title_active_node = $wp_admin_bar->get_node( 'asqn-active' );
		if ( $scripts ) { 
			$title_active_node->title = esc_html__( 'Active Scripts', 'advanced-scripts-quicknav' ) . ' (' . $count_active . ')';
		}
		$wp_admin_bar->add_node( $title_active_node );
		
		/** Set active counter for class */
		self::$scripts_active = $count_active;
		
		/** Second, iterate through all Scripts, use only In-Active ones! */
		if ( $scripts ) {
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-inactive',
				//'title'  => esc_html__( 'Inactive Scripts', 'advanced-scripts-quicknav' ) . ' (' . $count_inactive . ')',
				'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts' ) ),
				'parent' => 'asqn-group-status',
			) );
			
			foreach ( $scripts as $script ) {
				
				/** List only In-active scripts for now */
				if ( $script[ 'status' ] || 'folder' === $script[ 'type' ] ) {
					continue;
				}
		
				$count_inactive++;
		
				/** Consider parent folders */
				$parent = ( 0 !== $script[ 'parent' ] ) ? intval( $script[ 'parent' ] ) : '';
				$parent = ( ! empty( $parent ) ) ? '&parent=' . $parent : '';
				
				$edit_link = admin_url( 'tools.php?page=advanced-scripts' . $parent . '&edit=' . intval( $script[ 'term_id' ] ) );
				
				$icon = $this->get_icon( $script[ 'type' ] );
						
				$location = sprintf(
					' <span class="location">%s</span>',
					esc_html( $this->get_location( $script[ 'location' ] ) )
				);
				
				$wp_admin_bar->add_node( array(
					'id'     => 'asqn-script-' . intval( $script[ 'term_id' ] ),
					'title'  => $icon . esc_html( $script[ 'title' ] ) . $location,
					'href'   => esc_url( $edit_link ),
					'parent' => 'asqn-inactive',
				) );
			}  // end foreach
		}  // end if - in-active check
		
		/** Populate the "Inactive" title string with counter result after foreach iteration */
		$title_inactive_node = $wp_admin_bar->get_node( 'asqn-inactive' );
		if ( $scripts ) {
			$title_inactive_node->title = esc_html__( 'Inactive Scripts', 'advanced-scripts-quicknav' ) . ' (' . $count_inactive . ')';
		}
		$wp_admin_bar->add_node( $title_inactive_node );
		
		/** Set inactive counter for class */
		self::$scripts_inactive = $count_inactive;
	}

	/**
	 * Add group node for Scripts by folder
	 */
	private function add_scripts_by_folder_group( $wp_admin_bar ) {
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-group-folders',
			'parent' => 'ddw-advscripts-quicknav',
		) );
		
		$this->add_scripts_listings_submenu( $wp_admin_bar );
		//$this->add_snippets_type_submenu( $wp_admin_bar );
	}
	
	/**
	 * Types Group: Add Scripts listings submenu (by status)
	 */
	private function add_scripts_listings_submenu( $wp_admin_bar ) {
		
		if ( ! function_exists( 'cpas_scripts_manager' ) ) return $wp_admin_bar;
		
		/** Get all Scripts from the DB (official operator function) */
		$scripts = cpas_scripts_manager();
		$scripts = $scripts->scripts;
		
		$icon = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5V19H20V7H11.5858L9.58579 5H4ZM12.4142 5H21C21.5523 5 22 5.44772 22 6V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H10.4142L12.4142 5Z"></path></svg></span> ';
		
		$icon_parent = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 21C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H10.4142L12.4142 5H20C20.5523 5 21 5.44772 21 6V9H19V7H11.5858L9.58579 5H4V16.998L5.5 11H22.5L20.1894 20.2425C20.0781 20.6877 19.6781 21 19.2192 21H3ZM19.9384 13H7.06155L5.56155 19H18.4384L19.9384 13Z"></path></svg></span> ';
		
		$status = '';
		
		/** Plugin: Variable Inspector by bowo */
		do_action( 'inspect', [ 'scripts', $scripts ] );
		
		if ( $scripts ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-scripts-folders',
				'title'  => esc_html__( 'Scripts by Folder', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts' ) ),
				'parent' => 'asqn-group-folders',
			) );
			
			foreach ( $scripts as $script ) {
				
				/** List only folders for now */
				if ( 'folder' !== $script[ 'type' ] ) {
					continue;
				}
				
				/** Plugin: Variable Inspector by bowo */
				do_action( 'inspect', [ 'script', $script ] );
				
				$status = ( $script[ 'status' ] ) ? esc_html__( 'active', 'advanced-scripts-quicknav' ) : esc_html__( 'inactive', 'advanced-scripts-quicknav' );
				$status = sprintf(
					' <span class="status %s">%s</span>',
					( $script[ 'status' ] ) ? 'active' : 'inactive',
					$status
				);
				
				$parent_node = ( 0 !== $script[ 'parent' ] ) ? 'asqn-folder-' . intval( $script[ 'parent' ] ) : 'asqn-scripts-folders';
					
				$wp_admin_bar->add_node( array(
					'id'     => 'asqn-folder-' . intval( $script[ 'term_id' ] ),
					'title'  => $icon . esc_html( $script[ 'title' ] ) . $status,
					'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts&parent=' . intval( $script[ 'term_id' ] ) ) ),
					'parent' => $parent_node,
					'meta'   => array( 'class' => 'has-icon' ),
				) );
			}  // end foreach
		}  // end if
	}

	/**
	 * Add group node for New Scripts
	 */
	private function add_scripts_new_group( $wp_admin_bar ) {
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-group-new',
			'parent' => 'ddw-advscripts-quicknav',
		) );
		
		$this->add_scripts_new_submenu( $wp_admin_bar );
	}
	
	/**
	 * New Scripts Group: Add Code Scripts - New submenu
	 */
	private function add_scripts_new_submenu( $wp_admin_bar ) {
		
		$icon_add = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13.0001 10.9999L22.0002 10.9997L22.0002 12.9997L13.0001 12.9999L13.0001 21.9998L11.0001 21.9998L11.0001 12.9999L2.00004 13.0001L2 11.0001L11.0001 10.9999L11 2.00025L13 2.00024L13.0001 10.9999Z"></path></svg></span> ';
		
		/** Add New Snippet – also by type */
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-add-script',
			'title'  => $icon_add . esc_html__( 'Add New', 'advanced-scripts-quicknav' ) . $for_network,
			'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts&parent=0&edit=0' ) ),
			'parent' => 'asqn-group-new',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		/** WP's own "New Content" section: Add New Script */
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-wpnewcontent-add-script',
			'title'  => esc_html__( 'Code Script', 'advanced-scripts-quicknav' ),
			'href'   => esc_url( admin_url( 'tools.php?page=advanced-scripts&parent=0&edit=0' ) ),
			'parent' => 'new-content',
		) );
	}
	
	/**
	 * Add group node for Advanced Scripts "settings".
	 */
	private function add_settings_group( $wp_admin_bar ) {
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-group-settings',
			'parent' => 'ddw-advscripts-quicknav',
		) );
		
		//$this->add_settings_submenu( $wp_admin_bar );
		$this->add_expert_submenu( $wp_admin_bar );
	}
	
	/**
	 * Add expert mode submenu.
	 */
	private function add_expert_submenu( $wp_admin_bar ) {
		
		if ( ! $this->is_expert_mode() ) return $wp_admin_bar;
		
		$icon_dash = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 13C20 15.2091 19.1046 17.2091 17.6569 18.6569L19.0711 20.0711C20.8807 18.2614 22 15.7614 22 13 22 7.47715 17.5228 3 12 3 6.47715 3 2 7.47715 2 13 2 15.7614 3.11929 18.2614 4.92893 20.0711L6.34315 18.6569C4.89543 17.2091 4 15.2091 4 13 4 8.58172 7.58172 5 12 5 16.4183 5 20 8.58172 20 13ZM15.293 8.29297 10.793 12.793 12.2072 14.2072 16.7072 9.70718 15.293 8.29297Z"></path></svg></span> ';
		
		if ( defined( 'SYSTEM_DASHBOARD_VERSION' ) ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-system-dashboard',
				'title'  => $icon_dash . esc_html__( 'System Dashboard', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'index.php?page=system-dashboard' ) ),
				'parent' => 'asqn-group-settings',
				'meta'   => array( 'class' => 'has-icon' ),
			) );
		}
		
		$icon_info = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-sitehealth-info',
			'title'  => $icon_info . esc_html__( 'Site Health Info', 'advanced-scripts-quicknav' ),
			'href'   => esc_url( admin_url( 'site-health.php?tab=debug' ) ),
			'parent' => 'asqn-group-settings',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
		
		$icon_code = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM4 5V19H20V5H4ZM12 15H18V17H12V15ZM8.66685 12L5.83842 9.17157L7.25264 7.75736L11.4953 12L7.25264 16.2426L5.83842 14.8284L8.66685 12Z"></path></svg></span> ';
		
		if ( defined( 'VARIABLE_INSPECTOR_VERSION' ) ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-variable-inspector',
				'title'  => $icon_code . esc_html__( 'Variable Inspector', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'tools.php?page=variable-inspector' ) ),
				'parent' => 'asqn-group-settings',
				'meta'   => array( 'class' => 'has-icon' ),
			) );
		}
		
		$icon_bug = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 19.9C15.2822 19.4367 17 17.419 17 15V12C17 11.299 16.8564 10.6219 16.5846 10H7.41538C7.14358 10.6219 7 11.299 7 12V15C7 17.419 8.71776 19.4367 11 19.9V14H13V19.9ZM5.5358 17.6907C5.19061 16.8623 5 15.9534 5 15H2V13H5V12C5 11.3573 5.08661 10.7348 5.2488 10.1436L3.0359 8.86602L4.0359 7.13397L6.05636 8.30049C6.11995 8.19854 6.18609 8.09835 6.25469 8H17.7453C17.8139 8.09835 17.88 8.19854 17.9436 8.30049L19.9641 7.13397L20.9641 8.86602L18.7512 10.1436C18.9134 10.7348 19 11.3573 19 12V13H22V15H19C19 15.9534 18.8094 16.8623 18.4642 17.6907L20.9641 19.134L19.9641 20.866L17.4383 19.4077C16.1549 20.9893 14.1955 22 12 22C9.80453 22 7.84512 20.9893 6.56171 19.4077L4.0359 20.866L3.0359 19.134L5.5358 17.6907ZM8 6C8 3.79086 9.79086 2 12 2C14.2091 2 16 3.79086 16 6H8Z"></path></svg></span> ';
		
		/**
		 * We need double check here as there is the "Downdload Manager" plugin
		 *   with the same 'DLM' prefix & constant.
		 */
		if ( defined( 'DLM_SLUG' ) && 'debug-log-manager' === DLM_SLUG ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-debuglog-manager',
				'title'  => $icon_bug . esc_html__( 'Debug Log Manager', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'tools.php?page=debug-log-manager' ) ),
				'parent' => 'asqn-group-settings',
				'meta'   => array( 'class' => 'has-icon' ),
			) );
		}
		
		add_action( 'admin_bar_menu', array( $this, 'remove_adminbar_nodes' ), 9999 );
	}
	
	/**
	 * Add group node for Script/ Snippet Library items (external links)
	 */
	private function add_library_group( $wp_admin_bar ) {
		
		if ( defined( 'ASQN_DISABLE_LIBRARY' ) && 'yes' === sanitize_key( ASQN_DISABLE_LIBRARY ) ) {
			return $wp_admin_bar;
		}
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-library',
			'parent' => 'ddw-advscripts-quicknav',
			'meta'   => array( 'class' => 'ab-sub-secondary' ),
		) );
	}

	/**
	 * Libraries Group: Add linked Libraries submenu
	 */
	private function add_libraries_submenu( $wp_admin_bar ) {
		
		$icon = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12L18.3431 17.6569L16.9289 16.2426L21.1716 12L16.9289 7.75736L18.3431 6.34315L24 12ZM2.82843 12L7.07107 16.2426L5.65685 17.6569L0 12L5.65685 6.34315L7.07107 7.75736L2.82843 12ZM9.78845 21H7.66009L14.2116 3H16.3399L9.78845 21Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-libraries',
			'title'  => $icon . esc_html__( 'Find Snippets', 'advanced-scripts-quicknav' ),
			'href'   => '#',
			'parent' => 'asqn-footer',
			'meta'   => array( 'class' => 'has-icon' ),
		) );
	
		$codelibs = array(
			'codesnippets-cloud' => array(
				'title' => __( 'Code Snippets Cloud', 'advanced-scripts-quicknav' ),
				'url'   => 'https://codesnippets.cloud/search',
			),
			'wpsnippets-library' => array(
				'title' => __( 'WP Snippets Library', 'advanced-scripts-quicknav' ),
				'url'   => 'https://wpsnippets.org/library/',
			),
			'websquadron-codes' => array(
				'title' => __( 'Codes by Web Squadron', 'advanced-scripts-quicknav' ),
				'url'   => 'https://learn.websquadron.co.uk/codes/',
			),
			'wpsnippetclub-archive' => array(
				'title' => __( 'WP SnippetClub Archive', 'advanced-scripts-quicknav' ),
				'url'   => 'https://wpsnippet.club/snippet/',
			),
			'dplugins-code' => array(
				'title' => __( 'Snippets Library by dPlugins', 'advanced-scripts-quicknav' ),
				'url'   => 'https://code.dplugins.com/',
			),
			'wpcodebin' => array(
				'title' => __( 'WPCodeBin by WPCodeBox', 'advanced-scripts-quicknav' ),
				'url'   => 'https://wpcodebin.com/',
			),
			'wpcode-library' => array(
				'title' => __( 'Snippets Library by WPCode', 'advanced-scripts-quicknav' ),
				'url'   => 'https://library.wpcode.com/',
			),
		);
	
		/** Make code libs array filterable */
		apply_filters( 'ddw/quicknav/csn_codelibs', $codelibs );
	
		foreach ( $codelibs as $id => $info ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-link-' . sanitize_key( $id ),
				'title'  => esc_html( $info[ 'title' ] ),
				'href'   => esc_url( $info[ 'url' ] ),
				'parent' => 'asqn-libraries',
				'meta'   => array( 'target' => '_blank', 'rel' => 'nofollow noopener noreferrer' ),
			) );
		}  // end foreach
	}
					
	/**
	 * Add group node for footer items (Links & About)
	 */
	private function add_footer_group( $wp_admin_bar ) {
		
		if ( defined( 'ASQN_DISABLE_FOOTER' ) && 'yes' === sanitize_key( ASQN_DISABLE_FOOTER ) ) {
			return $wp_admin_bar;
		}
		
		$wp_admin_bar->add_group( array(
			'id'     => 'asqn-footer',
			'parent' => 'ddw-advscripts-quicknav',
			'meta'   => array( 'class' => 'ab-sub-secondary' ),
		) );
	}
	
	/**
	 * Footer Group: Add Links submenu
	 */
	private function add_links_submenu( $wp_admin_bar ) {
		
		$icon = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6V8H5V19H16V14H18V20C18 20.5523 17.5523 21 17 21H4C3.44772 21 3 20.5523 3 20V7C3 6.44772 3.44772 6 4 6H10ZM21 3V11H19L18.9999 6.413L11.2071 14.2071L9.79289 12.7929L17.5849 5H13V3H21Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-links',
			'title'  => $icon . esc_html__( 'Links', 'advanced-scripts-quicknav' ),
			'href'   => '#',
			'parent' => 'asqn-footer',
			'meta'   => array( 'class' => 'has-icon' ),
		) );

		$links = array(
			'as-cleanplugins' => array(
				'title' => __( 'Advanced Scripts', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.cleanplugins.com/products/advanced-scripts/',
			),
			'as-emergency' => array(
				'title' => __( 'Emergency Fixes 🔥', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.cleanplugins.com/blog/advanced-scripts-2-4-0-release-overview/',
			),
			'cp-blog' => array(
				'title' => __( 'Clean Plugins Blog', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.cleanplugins.com/blog/',
			),
			'cp-youtube' => array(
				'title' => __( 'CP YouTube Channel', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.youtube.com/c/cleanplugins',
			),
			'cp-fb-group' => array(
				'title' => __( 'CP Facbook Group (official)', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.facebook.com/groups/cleanplugins',
			),
		);

		/** Make links array filterable */
		apply_filters( 'ddw/quicknav/as_links', $links );
		
		foreach ( $links as $id => $info ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-link-' . sanitize_key( $id ),
				'title'  => esc_html( $info[ 'title' ] ),
				'href'   => esc_url( $info[ 'url' ] ),
				'parent' => 'asqn-links',
				'meta'   => array( 'target' => '_blank', 'rel' => 'nofollow noopener noreferrer' ),
			) );
		}  // end foreach
	}

	/**
	 * Footer Group: Add About submenu
	 */
	private function add_about_submenu( $wp_admin_bar ) {
		
		$icon = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.841 15.659L18.017 15.836L18.1945 15.659C19.0732 14.7803 20.4978 14.7803 21.3765 15.659C22.2552 16.5377 22.2552 17.9623 21.3765 18.841L18.0178 22.1997L14.659 18.841C13.7803 17.9623 13.7803 16.5377 14.659 15.659C15.5377 14.7803 16.9623 14.7803 17.841 15.659ZM12 14V16C8.68629 16 6 18.6863 6 22H4C4 17.6651 7.44784 14.1355 11.7508 14.0038L12 14ZM12 1C15.315 1 18 3.685 18 7C18 10.2397 15.4357 12.8776 12.225 12.9959L12 13C8.685 13 6 10.315 6 7C6 3.76034 8.56434 1.12237 11.775 1.00414L12 1ZM12 3C9.78957 3 8 4.78957 8 7C8 9.21043 9.78957 11 12 11C14.2104 11 16 9.21043 16 7C16 4.78957 14.2104 3 12 3Z"></path></svg></span> ';
		
		$wp_admin_bar->add_node( array(
			'id'     => 'asqn-about',
			'title'  => $icon . esc_html__( 'About', 'advanced-scripts-quicknav' ),
			'href'   => '#',
			'parent' => 'asqn-footer',
			'meta'   => array( 'class' => 'has-icon' ),
		) );

		$about_links = array(
			'author' => array(
				'title' => __( 'Author: David Decker', 'advanced-scripts-quicknav' ),
				'url'   => 'https://deckerweb.de/',
			),
			'github' => array(
				'title' => __( 'Plugin on GitHub', 'advanced-scripts-quicknav' ),
				'url'   => 'https://github.com/deckerweb/snippets-quicknav',
			),
			'kofi' => array(
				'title' => __( 'Buy Me a Coffee', 'advanced-scripts-quicknav' ),
				'url'   => 'https://ko-fi.com/deckerweb',
			),
		);

		foreach ( $about_links as $id => $info ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-about-' . sanitize_key( $id ),
				'title'  => esc_html( $info[ 'title' ] ),
				'href'   => esc_url( $info[ 'url' ] ),
				'parent' => 'asqn-about',
				'meta'   => array( 'target' => '_blank', 'rel' => 'nofollow noopener noreferrer' ),
			) );
		}  // end foreach
	}
	
	/**
	 * Show the Admin Bar also in Block Editor full screen mode.
	 */
	public function adminbar_block_editor_fullscreen() {
		
		if ( ! is_admin_bar_showing() ) {
			return;
		}
		
		/**
		 * Depending on user color scheme get proper bg color value for admin bar.
		 */
		$user_color_scheme = get_user_option( 'admin_color' );
		$admin_scheme      = $this->get_scheme_colors();
		
		$bg_color = $admin_scheme[ $user_color_scheme ][ 'bg' ];
		
		$inline_css = sprintf(
			'
				@media (min-width: 600px) {
					body.is-fullscreen-mode .block-editor__container {
						top: var(--wp-admin--admin-bar--height);
					}
				}
				
				@media (min-width: 782px) {
					body.js.is-fullscreen-mode #wpadminbar {
						display: block;
					}
				
					body.is-fullscreen-mode .block-editor__container {
						min-height: calc(100vh - var(--wp-admin--admin-bar--height));
					}
				
					body.is-fullscreen-mode .edit-post-layout .editor-post-publish-panel {
						top: var(--wp-admin--admin-bar--height);
					}
					
					.edit-post-fullscreen-mode-close.components-button {
						background: %s;
					}
					
					.edit-post-fullscreen-mode-close.components-button::before {
						box-shadow: none;
					}
				}
				
				@media (min-width: 783px) {
					.is-fullscreen-mode .interface-interface-skeleton {
						top: var(--wp-admin--admin-bar--height);
					}
				}
			',
			sanitize_hex_color( $bg_color )
		);
		
		wp_add_inline_style( 'wp-block-editor', $inline_css );
		
		add_action( 'admin_bar_menu', array( $this, 'remove_adminbar_nodes' ), 999 );
	}
	
	/**
	 * Remove Admin Bar nodes.
	 */
	public function remove_adminbar_nodes( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );  
	}
	
	/**
	 * Add additional plugin related info to the Site Health Debug Info section.
	 *
	 * @link https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
	 *
	 * @param array $debug_info Array holding all Debug Info items.
	 * @return array Modified array of Debug Info.
	 */
	public function site_health_debug_info( $debug_info ) {
	
		$string_undefined = esc_html_x( 'Undefined', 'Site Health Debug info', 'advanced-scripts-quicknav' );
		$string_enabled   = esc_html_x( 'Enabled', 'Site Health Debug info', 'advanced-scripts-quicknav' );
		$string_disabled  = esc_html_x( 'Disabled', 'Site Health Debug info', 'advanced-scripts-quicknav' );
		$string_value     = ' – ' . esc_html_x( 'value', 'Site Health Debug info', 'advanced-scripts-quicknav' ) . ': ';
		$string_version   = defined( 'EPXADVSC_VER' ) ? EPXADVSC_VER : '';
	
		/** Add our Debug info */
		$debug_info[ 'advanced-scripts-quicknav' ] = array(
			'label'  => esc_html__( 'Advanced Scripts QuickNav', 'advanced-scripts-quicknav' ) . ' (' . esc_html__( 'Plugin', 'advanced-scripts-quicknav' ) . ')',
			'fields' => array(
	
				/** Various values */
				'asqn_plugin_version' => array(
					'label' => esc_html__( 'Plugin version', 'advanced-scripts-quicknav' ),
					'value' => self::VERSION,
				),
				'asqn_install_type' => array(
					'label' => esc_html__( 'WordPress Install Type', 'advanced-scripts-quicknav' ),
					'value' => ( is_multisite() ? esc_html__( 'Multisite install', 'advanced-scripts-quicknav' ) : esc_html__( 'Single Site install', 'advanced-scripts-quicknav' ) ),
				),
	
				/** Advanced Scripts QuickNav constants */
				'ASQN_VIEW_CAPABILITY' => array(
					'label' => 'ASQN_VIEW_CAPABILITY',
					'value' => ( ! defined( 'ASQN_VIEW_CAPABILITY' ) ? $string_undefined : ( ASQN_VIEW_CAPABILITY ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_NAME_IN_ADMINBAR' => array(
					'label' => 'ASQN_NAME_IN_ADMINBAR',
					'value' => ( ! defined( 'ASQN_NAME_IN_ADMINBAR' ) ? $string_undefined : ( ASQN_NAME_IN_ADMINBAR ? $string_enabled . $string_value . esc_html( ASQN_NAME_IN_ADMINBAR )  : $string_disabled ) ),
				),
				'ASQN_COUNTER' => array(
					'label' => 'ASQN_COUNTER',
					'value' => ( ! defined( 'ASQN_COUNTER' ) ? $string_undefined : ( ASQN_COUNTER ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_ICON' => array(
					'label' => 'ASQN_ICON',
					'value' => ( ! defined( 'ASQN_ICON' ) ? $string_undefined : ( ASQN_ICON ? $string_enabled . $string_value . sanitize_key( ASQN_ICON ) : $string_disabled ) ),
				),
				'ASQN_DISABLE_LIBRARY' => array(
					'label' => 'ASQN_DISABLE_LIBRARY',
					'value' => ( ! defined( 'ASQN_DISABLE_LIBRARY' ) ? $string_undefined : ( ASQN_DISABLE_LIBRARY ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_DISABLE_FOOTER' => array(
					'label' => 'ASQN_DISABLE_FOOTER',
					'value' => ( ! defined( 'ASQN_DISABLE_FOOTER' ) ? $string_undefined : ( ASQN_DISABLE_FOOTER ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_EXPERT_MODE' => array(
					'label' => 'ASQN_EXPERT_MODE',
					'value' => ( ! defined( 'ASQN_EXPERT_MODE' ) ? $string_undefined : ( ASQN_EXPERT_MODE ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_AS_SAFE_MODE' => array(
					'label' => 'AS_SAFE_MODE',
					'value' => ( ! defined( 'AS_SAFE_MODE' ) ? $string_undefined : ( AS_SAFE_MODE ? $string_enabled : $string_disabled ) ),
				),
				'ASQN_SCRIPT_DEBUG' => array(
					'label' => 'SCRIPT_DEBUG',
					'value' => ( ! defined( 'SCRIPT_DEBUG' ) ? $string_undefined : ( SCRIPT_DEBUG ? $string_enabled : $string_disabled ) ),
				),
				'asqn_as_version' => array(
					'label' => esc_html( 'Advanced Scripts Version', 'advanced-scripts-quicknav' ),
					'value' => ( ! defined( 'EPXADVSC_VER' ) ? esc_html__( 'Plugin not installed', 'advanced-scripts-quicknav' ) : $string_version ),
				),
			),  // end array
		);
	
		/** Return modified Debug Info array */
		return $debug_info;
	}
	
}  // end of class

/** Start instance of Class */
new DDW_Advanced_Scripts_QuickNav();
	
endif;