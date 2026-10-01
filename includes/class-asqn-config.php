<?php
/** Effective personal preferences; existing constants take precedence. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Config {
	public const VERSION = '1.2.0';
	public static function key( string $suffix ): string { return 'asqn_' . $suffix . '_' . get_current_blog_id(); }
	public static function preferences(): array {
		$value = get_user_meta( get_current_user_id(), self::key( 'preferences' ), true );
		return array_merge( [ 'counter' => false, 'expert' => true, 'limit' => 40 ], is_array( $value ) ? array_intersect_key( $value, [ 'counter' => true, 'expert' => true, 'limit' => true ] ) : [] );
	}
	public static function flag( string $constant, bool $default ): bool {
		if ( ! defined( $constant ) ) { return $default; }
		$value = constant( $constant );
		return true === $value || 1 === $value || in_array( strtolower( (string) $value ), [ 'yes', 'true', '1', 'on' ], true );
	}
	public static function capability(): string {
		return defined( 'ASQN_VIEW_CAPABILITY' ) && is_string( ASQN_VIEW_CAPABILITY ) && '' !== ASQN_VIEW_CAPABILITY ? sanitize_key( ASQN_VIEW_CAPABILITY ) : 'activate_plugins';
	}
	public static function allowed(): bool {
		if ( ! current_user_can( 'manage_options' ) || ! current_user_can( self::capability() ) ) { return false; }
		if ( ! defined( 'ASQN_ENABLED_USERS' ) ) { return true; }
		return in_array( get_current_user_id(), array_map( 'absint', (array) ASQN_ENABLED_USERS ), true );
	}
	public static function visible(): bool { return self::allowed() && ASQN_Advanced_Scripts_Adapter::available() && is_admin_bar_showing(); }
	public static function counter(): bool { return self::flag( 'ASQN_COUNTER', (bool) self::preferences()['counter'] ); }
	public static function expert(): bool { return self::flag( 'ASQN_EXPERT_MODE', (bool) self::preferences()['expert'] ); }
	public static function limit(): int {
		$value = defined( 'ASQN_MENU_LIMIT' ) ? ASQN_MENU_LIMIT : self::preferences()['limit'];
		return max( 10, min( 200, absint( $value ) ) );
	}
}
