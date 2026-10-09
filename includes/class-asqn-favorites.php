<?php
/** Favorites belong to the current user and current site. */
defined( 'ABSPATH' ) || exit;
/** Validate personal favorites without modifying Advanced Scripts terms. */
final class ASQN_Favorites {

	/**
	 * Read valid favorites for the current user and site.
	 *
	 * @param array $scripts Indexed script metadata.
	 * @return array Valid, unique personal favorite IDs.
	 */
	public static function ids( array $scripts ): array {
		$saved = get_user_meta( get_current_user_id(), ASQN_Config::key( 'favorites' ), true );
		return self::sanitize( is_array( $saved ) ? $saved : [], $scripts );
	}

	/**
	 * Deduplicate existing snippet IDs and enforce the favorites limit.
	 *
	 * @param array $ids Candidate favorite term IDs.
	 * @param array $scripts Indexed script metadata.
	 * @return array At most 100 valid, unique snippet term IDs.
	 */
	public static function sanitize( array $ids, array $scripts ): array {
		$result = [];
		foreach ( $ids as $value ) {
			if ( ! is_scalar( $value ) ) { continue; }
			$id = absint( $value );
			if ( isset( $scripts[$id] ) && 'folder' !== $scripts[$id]['type'] ) { $result[$id] = $id; }
		}
		return array_slice( array_values( $result ), 0, 100 );
	}
}
