<?php
/** Read-only boundary to Advanced Scripts; never loads or executes script code. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Advanced_Scripts_Adapter {
	public static function available(): bool { return defined( 'EPXADVSC_VER' ) && function_exists( 'cpas_scripts_manager' ); }
	public static function safe_mode(): bool {
		return defined( 'AS_SAFE_MODE' ) ? (bool) AS_SAFE_MODE : (bool) get_option( 'advanced-scripts-safemode', false );
	}
	public static function url( int $parent = 0, ?int $edit = null ): string {
		$args = [ 'page' => 'advanced-scripts', 'parent' => $parent ];
		if ( null !== $edit ) { $args['edit'] = $edit; }
		return add_query_arg( $args, admin_url( 'tools.php' ) );
	}
	/** Keep upstream order and expose metadata only, with safe defaults. */
	public function scripts(): array {
		if ( ! self::available() ) { return []; }
		$manager = cpas_scripts_manager();
		if ( ! is_object( $manager ) || ! method_exists( $manager, 'get_scripts' ) ) { return []; }
		$raw = $manager->get_scripts();
		$result = [];
		foreach ( is_array( $raw ) ? $raw : [] as $item ) {
			if ( ! is_array( $item ) || empty( $item['term_id'] ) ) { continue; }
			$id = absint( $item['term_id'] );
			if ( ! $id ) { continue; }
			$result[$id] = [ 'term_id' => $id, 'parent' => absint( $item['parent'] ?? 0 ), 'status' => ! empty( $item['status'] ) ];
			foreach ( [ 'title', 'type', 'location', 'hook' ] as $key ) {
				$result[$id][$key] = isset( $item[$key] ) && is_scalar( $item[$key] ) ? (string) $item[$key] : '';
			}
		}
		return $result;
	}
}
