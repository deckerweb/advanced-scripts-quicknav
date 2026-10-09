<?php
/** Read-only boundary to Advanced Scripts; never loads or executes script code. */
defined( 'ABSPATH' ) || exit;
/** Expose read-only Advanced Scripts metadata and native navigation URLs. */
final class ASQN_Advanced_Scripts_Adapter {

	/**
	 * Check the public Advanced Scripts integration entrypoints.
	 *
	 * @return bool Whether the public Advanced Scripts entrypoints are available.
	 */
	public static function available(): bool { return defined( 'EPXADVSC_VER' ) && function_exists( 'cpas_scripts_manager' ); }

	/**
	 * Resolve the effective PHP Safe Mode without changing it.
	 *
	 * @return bool Effective PHP Safe Mode flag.
	 */
	public static function safe_mode(): bool {
		return defined( 'AS_SAFE_MODE' ) ? (bool) AS_SAFE_MODE : (bool) get_option( 'advanced-scripts-safemode', false );
	}

	/**
	 * Build the native Advanced Scripts folder or editor URL.
	 *
	 * @param int $parent Parent toolbar node ID or folder term ID.
	 * @param int|null $edit Snippet term ID, or null for a listing.
	 * @return string Native listing or editor URL.
	 */
	public static function url( int $parent = 0, ?int $edit = null ): string {
		$args = [ 'page' => 'advanced-scripts', 'parent' => $parent ];
		if ( null !== $edit ) { $args['edit'] = $edit; }
		return add_query_arg( $args, admin_url( 'tools.php' ) );
	}
	/**
	 * Return indexed script metadata without exposing source code.
	 *
	 * @return array Indexed script metadata, excluding script source code.
	 */
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
