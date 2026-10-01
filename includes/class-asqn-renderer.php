<?php
/** Toolbar presentation and existing optional resource integrations. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Admin_Bar_Renderer {
	private ASQN_Navigation_Builder $navigation;

	/** Class constants & variables */
	private const VERSION = ASQN_Config::VERSION;
		

	/**
	 * Constructor
	 */
	public function __construct( ASQN_Navigation_Builder $navigation ) { $this->navigation = $navigation; }
	
	/**
	 * Is expert mode active?
	 *   Gives some more stuff that is more focused at (plugin/snippet) developers
	 *   and mostly not needed for fast site building.
	 *
	 * @return bool
	 */
	private function is_expert_mode(): bool { return ASQN_Config::expert(); }
	
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
		if ( ! ASQN_Config::visible() ) { return; }
		
		/**
		 * Depending on user color scheme get proper base and hover color values for the main item (svg) icon.
		 */
		$user_color_scheme = get_user_option( 'admin_color' );
		$user_color_scheme = ( is_admin() || is_network_admin() ) ? $user_color_scheme : 'fresh';  // b/c in frontend there is no 'admin_color'
		$admin_scheme      = $this->get_scheme_colors();
		$user_color_scheme = isset( $admin_scheme[$user_color_scheme] ) ? $user_color_scheme : 'fresh';
		
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
				
				#wpadminbar .asqn-scripts-list .has-icon .icon-svg svg {
					display: inline-block;
					margin-bottom: 3px;
					vertical-align: middle;
					width: 16px;
					height: 16px;
				}
				
				#wpadminbar .asqn-scripts-list .icon-svg.ab-icon svg {
					width: 15px;
					height: 15px;
				}
				
				.asqn-scripts-list .ab-item .icon-svg.ab-icon svg {
					color: %5$s;
				}
				
				.asqn-scripts-list .ab-item:hover .icon-svg.ab-icon svg {
					color: %6$s;
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
	private function is_safe_mode_active(): bool { return ASQN_Advanced_Scripts_Adapter::safe_mode(); }
	
	/**
	 * Get number of all Scripts (without folders). (helper function)
	 *
	 * @return int Number of scripts.
	 */
	private function script_counter(): int { return $this->navigation->count(); }
	
	/**
	 * Adds the main Scripts (Code Snippets) menu and its submenus to the Admin Bar.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance.
	 */
	public function add_admin_bar_menu( $wp_admin_bar ) {
		
		if ( ! ASQN_Config::visible() ) { return; }

		/** Build the main item title, optional scripts count value */
		$all_scripts = $this->script_counter();
		$counter     = ASQN_Config::counter() ? ' (' . intval( $all_scripts ) . ')' : '';
		$asqn_name   = ( defined( 'ASQN_NAME_IN_ADMINBAR' ) ) ? esc_html( ASQN_NAME_IN_ADMINBAR ) : esc_html__( 'Scripts', 'advanced-scripts-quicknav' );
		$asqn_name   = $asqn_name . $counter;

		/** Default "script icon" */
		$code_icon = '<span class="icon-svg ab-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M9 8l-4 4 4 4m6-8 4 4-4 4m-2-9-2 10" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="m18 2-2 5 5-2z" fill="currentColor"/></svg></span> ';
		
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
		
		$wp_admin_bar->add_node( [ 'id' => 'asqn-preferences', 'parent' => 'ddw-advscripts-quicknav', 'title' => esc_html__( 'QuickNav preferences', 'advanced-scripts-quicknav' ), 'href' => esc_url( admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ) ] );
		/** Add submenus */
		$this->add_safemode_submenu( $wp_admin_bar );  // group node
		$this->navigation->favorites( $wp_admin_bar );
		$this->add_scripts_by_status_group( $wp_admin_bar );  // group node
		$this->add_scripts_by_folder_group( $wp_admin_bar );  // group node
		$this->add_scripts_new_group( $wp_admin_bar );  // group node
		$this->add_settings_group( $wp_admin_bar );  // group node
		$this->add_library_group( $wp_admin_bar );  // group node
		if ( ! ASQN_Config::flag( 'ASQN_DISABLE_LIBRARY', false ) ) { $this->add_libraries_submenu( $wp_admin_bar ); }
		$this->add_footer_group( $wp_admin_bar );  // group node
		if ( ! ASQN_Config::flag( 'ASQN_DISABLE_FOOTER', false ) ) {
			$this->add_links_submenu( $wp_admin_bar );
			$this->add_about_submenu( $wp_admin_bar );
		}
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
	private function add_scripts_to_admin_bar( $wp_admin_bar ): void { $this->navigation->statuses( $wp_admin_bar ); }

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
	private function add_scripts_listings_submenu( $wp_admin_bar ): void { $this->navigation->folders( $wp_admin_bar ); }

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
			'title'  => $icon_add . esc_html__( 'Add New', 'advanced-scripts-quicknav' ),
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
		
		$icon_codebox = '<span class="icon-svg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM4 5V19H20V5H4ZM20 12L16.4645 15.5355L15.0503 14.1213L17.1716 12L15.0503 9.87868L16.4645 8.46447L20 12ZM6.82843 12L8.94975 14.1213L7.53553 15.5355L4 12L7.53553 8.46447L8.94975 9.87868L6.82843 12ZM11.2443 17H9.11597L12.7557 7H14.884L11.2443 17Z"></path></svg></span> ';
		
		if ( defined( 'DPDEVKIT_URL' ) && current_user_can( 'manage_options' ) ) {
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devkitpro',
				'title'  => $icon_codebox . 'DevKit Pro',
				'href'   => esc_url( admin_url( 'admin.php?page=devkit' ) ),
				'parent' => 'asqn-group-settings',
				'meta'   => array( 'class' => 'has-icon' ),
			) );
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devkitpro-filemanager',
				'title'  => esc_html__( 'File Manager', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'admin.php?page=devkit&tab=file-manager' ) ),
				'parent' => 'asqn-devkitpro',
				'meta'   => array( 'target' => '_blank' ),
			) );
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devkitpro-errorlogs',
				'title'  => esc_html__( 'Error Logs', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'admin.php?page=devkit&tab=error-log' ) ),
				'parent' => 'asqn-devkitpro',
				'meta'   => array( 'target' => '_blank' ),
			) );
			
			if ( 'yes' === get_option( 'dpdevkit_adminer' ) ) {
				$wp_admin_bar->add_node( array(
					'id'     => 'asqn-devkitpro-adminer',
					'title'  => esc_html__( 'Adminer: Manage DB', 'advanced-scripts-quicknav' ),
					'href'   => esc_url( site_url() . '/devkit-adminer' ),
					'parent' => 'asqn-devkitpro',
					'meta'   => array( 'target' => '_blank' ),
				) );
			}
			
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-devkitpro-tools',
				'title'  => esc_html__( 'Tools', 'advanced-scripts-quicknav' ),
				'href'   => esc_url( admin_url( 'admin.php?page=devkit&tab=tools' ) ),
				'parent' => 'asqn-devkitpro',
				'meta'   => array( 'target' => '_blank' ),
			) );
		}
		
	}
	
	/**
	 * Add group node for Script/ Snippet Library items (external links)
	 */
	private function add_library_group( $wp_admin_bar ) {
		
		if ( ASQN_Config::flag( 'ASQN_DISABLE_LIBRARY', false ) ) {
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
			'parent' => 'asqn-library',
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
		$codelibs = apply_filters( 'ddw/quicknav/csn_codelibs', $codelibs );
		$codelibs = is_array( $codelibs ) ? $codelibs : [];
	
		foreach ( $codelibs as $id => $info ) {
			if ( ! is_array( $info ) || ! is_string( $info['title'] ?? null ) || ! is_string( $info['url'] ?? null ) ) { continue; }
			$wp_admin_bar->add_node( array(
				'id'     => 'asqn-library-link-' . sanitize_key( $id ),
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
		
		if ( ASQN_Config::flag( 'ASQN_DISABLE_FOOTER', false ) ) {
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
				'url'   => 'https://r.freemius.com/6334/142255/',
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
				'title' => __( 'CP Facebook Group (official)', 'advanced-scripts-quicknav' ),
				'url'   => 'https://www.facebook.com/groups/cleanplugins',
			),
		);

		/** Make links array filterable */
		$links = apply_filters( 'ddw/quicknav/as_links', $links );
		$links = is_array( $links ) ? $links : [];
		
		foreach ( $links as $id => $info ) {
			if ( ! is_array( $info ) || ! is_string( $info['title'] ?? null ) || ! is_string( $info['url'] ?? null ) ) { continue; }
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
				'url'   => 'https://github.com/deckerweb/advanced-scripts-quicknav',
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
	 * Add additional plugin related info to the Site Health Debug Info section.
	 *
	 * @link https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
	 *
	 * @param array $debug_info Array holding all Debug Info items.
	 * @return array Modified array of Debug Info.
	 */
	public function site_health_debug_info( $debug_info ) {
		$fields = [
			'quicknav' => [ 'label' => __( 'Plugin version', 'advanced-scripts-quicknav' ), 'value' => ASQN_Config::VERSION ],
			'advanced_scripts' => [ 'label' => 'Advanced Scripts', 'value' => defined( 'EPXADVSC_VER' ) ? EPXADVSC_VER : __( 'Not active', 'advanced-scripts-quicknav' ) ],
			'capability' => [ 'label' => 'ASQN_VIEW_CAPABILITY', 'value' => ASQN_Config::capability() ],
			'safe_mode' => [ 'label' => __( 'Safe Mode', 'advanced-scripts-quicknav' ), 'value' => $this->is_safe_mode_active() ? __( 'Enabled', 'advanced-scripts-quicknav' ) : __( 'Disabled', 'advanced-scripts-quicknav' ) ],
			'limit' => [ 'label' => __( 'Menu entry limit', 'advanced-scripts-quicknav' ), 'value' => ASQN_Config::limit() ],
		];
		foreach ( [ 'counter' => ASQN_Config::counter(), 'expert' => ASQN_Config::expert(), 'library' => ! ASQN_Config::flag( 'ASQN_DISABLE_LIBRARY', false ), 'footer' => ! ASQN_Config::flag( 'ASQN_DISABLE_FOOTER', false ) ] as $key => $value ) {
			$fields[$key] = [ 'label' => $key, 'value' => $value ? __( 'Enabled', 'advanced-scripts-quicknav' ) : __( 'Disabled', 'advanced-scripts-quicknav' ) ];
		}
		$debug_info['advanced-scripts-quicknav'] = [ 'label' => 'Advanced Scripts QuickNav', 'fields' => $fields ];
		return $debug_info;
	}
}
