<?php
/** Effective personal preferences; existing constants take precedence. */
defined( 'ABSPATH' ) || exit;
/** Resolve permissions and site-scoped personal display preferences. */
final class ASQN_Config {
	public const VERSION = '1.3.2';

	/**
	 * Build the current-site user-metadata key.
	 *
	 * @param string $suffix Metadata-key suffix.
	 * @return string Site-scoped user-metadata key.
	 */
	public static function key( string $suffix ): string { return 'asqn_' . $suffix . '_' . get_current_blog_id(); }

	/**
	 * Read personal preferences with conservative defaults.
	 *
	 * @return array Personal counter, expert-links and entry-limit preferences.
	 */
	public static function preferences(): array {
		$value = get_user_meta( get_current_user_id(), self::key( 'preferences' ), true );
		return array_merge( [ 'counter' => false, 'expert' => true, 'limit' => 40 ], is_array( $value ) ? array_intersect_key( $value, [ 'counter' => true, 'expert' => true, 'limit' => true ] ) : [] );
	}

	/**
	 * Resolve a boolean configuration constant.
	 *
	 * @param string $constant Configuration constant name.
	 * @param bool $default Fallback flag when the constant is absent.
	 * @return bool Effective boolean value after constant precedence.
	 */
	public static function flag( string $constant, bool $default ): bool {
		if ( ! defined( $constant ) ) { return $default; }
		$value = constant( $constant );
		return true === $value || 1 === $value || in_array( strtolower( (string) $value ), [ 'yes', 'true', '1', 'on' ], true );
	}

	/**
	 * Resolve the required navigation capability.
	 *
	 * @return string Required WordPress capability name.
	 */
	public static function capability(): string {
		return defined( 'ASQN_VIEW_CAPABILITY' ) && is_string( ASQN_VIEW_CAPABILITY ) && '' !== ASQN_VIEW_CAPABILITY ? sanitize_key( ASQN_VIEW_CAPABILITY ) : 'activate_plugins';
	}

	/**
	 * Check capabilities and the optional user allowlist.
	 *
	 * @return bool Whether the current user may access QuickNav.
	 */
	public static function allowed(): bool {
		if ( ! current_user_can( 'manage_options' ) || ! current_user_can( self::capability() ) ) { return false; }
		if ( ! defined( 'ASQN_ENABLED_USERS' ) ) { return true; }
		return in_array( get_current_user_id(), array_map( 'absint', (array) ASQN_ENABLED_USERS ), true );
	}

	/**
	 * Check whether navigation may appear in the current request.
	 *
	 * @return bool Whether the current request may display the toolbar.
	 */
	public static function visible(): bool { return self::allowed() && ASQN_Advanced_Scripts_Adapter::available() && is_admin_bar_showing(); }

	/**
	 * Resolve the effective snippet-counter preference.
	 *
	 * @return bool Whether to display the snippet counter.
	 */
	public static function counter(): bool { return self::flag( 'ASQN_COUNTER', (bool) self::preferences()['counter'] ); }

	/**
	 * Resolve the effective developer-links preference.
	 *
	 * @return bool Whether to display developer links.
	 */
	public static function expert(): bool { return self::flag( 'ASQN_EXPERT_MODE', (bool) self::preferences()['expert'] ); }

	/**
	 * Clamp the effective menu-entry budget.
	 *
	 * @return int Entry limit clamped between 10 and 200.
	 */
	public static function limit(): int {
		$value = defined( 'ASQN_MENU_LIMIT' ) ? ASQN_MENU_LIMIT : self::preferences()['limit'];
		return max( 10, min( 200, absint( $value ) ) );
	}
}
