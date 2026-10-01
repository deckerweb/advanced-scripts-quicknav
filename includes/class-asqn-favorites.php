<?php
/** Favorites belong to the current user and current site. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Favorites {
	public static function ids( array $scripts ): array {
		$saved = get_user_meta( get_current_user_id(), ASQN_Config::key( 'favorites' ), true );
		return self::sanitize( is_array( $saved ) ? $saved : [], $scripts );
	}
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
