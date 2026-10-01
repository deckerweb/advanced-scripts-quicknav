<?php
/** Project flat upstream metadata into toolbar groups and a guarded folder tree. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Navigation_Builder {
	private array $scripts;
	private array $children = [];
	public function __construct( array $scripts ) {
		$this->scripts = $scripts;
		foreach ( $scripts as $item ) { $this->children[$item['parent']][] = $item['term_id']; }
	}
	public function scripts(): array { return $this->scripts; }
	public function count(): int { return count( array_filter( $this->scripts, static function( $item ) { return 'folder' !== $item['type']; } ) ); }
	/** Saved status counts, with ancestor blocking reported as an additional dimension. */
	public function statistics(): array {
		$stats = [ 'total' => 0, 'active' => 0, 'inactive' => 0, 'blocked' => 0, 'folders' => 0, 'types' => [] ];
		foreach ( $this->scripts as $item ) {
			if ( 'folder' === $item['type'] ) { $stats['folders']++; continue; }
			$stats['total']++;
			$stats[$item['status'] ? 'active' : 'inactive']++;
			if ( $item['status'] && $this->blocked( $item ) ) { $stats['blocked']++; }
			$type = self::type( $item['type'] );
			$stats['types'][$type] = ( $stats['types'][$type] ?? 0 ) + 1;
		}
		arsort( $stats['types'] );
		return $stats;
	}
	/** Own enabled flag is distinct from ancestor blocking and actual execution. */
	public function blocked( array $item ): bool {
		$seen = [];
		$parent = $item['parent'];
		while ( $parent && isset( $this->scripts[$parent] ) && ! isset( $seen[$parent] ) ) {
			$seen[$parent] = true;
			$folder = $this->scripts[$parent];
			if ( 'folder' === $folder['type'] && ! $folder['status'] ) { return true; }
			$parent = $folder['parent'];
		}
		return false;
	}
	public function path( array $item ): string {
		$parts = []; $seen = []; $parent = $item['parent'];
		while ( $parent && isset( $this->scripts[$parent] ) && ! isset( $seen[$parent] ) ) {
			$seen[$parent] = true; $folder = $this->scripts[$parent];
			array_unshift( $parts, $folder['title'] ); $parent = $folder['parent'];
		}
		return implode( ' / ', $parts );
	}
	public static function type( string $type ): string {
		$types = [ 'application/x-httpd-php' => 'PHP', 'url/javascript' => 'JS', 'text/javascript' => 'JS', 'url/css' => 'CSS', 'text/css' => 'CSS', 'text/x-scss' => 'SCSS', 'text/x-scss-partial' => __( 'SCSS partial', 'advanced-scripts-quicknav' ), 'text/x-less' => 'LESS', 'text/html' => 'HTML', 'folder' => __( 'Folder', 'advanced-scripts-quicknav' ) ];
		return $types[$type] ?? __( 'Code', 'advanced-scripts-quicknav' );
	}
	public function description( array $item ): string {
		$locations = [ 'all' => __( 'everywhere', 'advanced-scripts-quicknav' ), 'front' => __( 'front-end', 'advanced-scripts-quicknav' ), 'admin' => __( 'administration', 'advanced-scripts-quicknav' ), 'shortcode' => __( 'shortcode', 'advanced-scripts-quicknav' ) ];
		$parts = [ self::type( $item['type'] ), $item['status'] ? __( 'active', 'advanced-scripts-quicknav' ) : __( 'inactive', 'advanced-scripts-quicknav' ), $this->path( $item ), $locations[$item['location']] ?? $item['location'], $item['hook'] ];
		if ( $this->blocked( $item ) ) { $parts[] = __( 'Disabled by folder', 'advanced-scripts-quicknav' ); }
		return implode( ' · ', array_filter( $parts ) );
	}
	private function script_node( $bar, array $item, string $parent, string $context ): void {
		$blocked = $this->blocked( $item );
		$label = esc_html( $item['title'] ?: __( 'Untitled script', 'advanced-scripts-quicknav' ) );
		$label .= ' <span class="asqn-type">' . esc_html( self::type( $item['type'] ) ) . '</span>';
		if ( $blocked ) { $label .= ' <span class="asqn-blocked">' . esc_html__( 'Disabled by folder', 'advanced-scripts-quicknav' ) . '</span>'; }
		elseif ( ! $item['status'] ) { $label .= ' <span class="asqn-muted">' . esc_html__( 'inactive', 'advanced-scripts-quicknav' ) . '</span>'; }
		// Preserve legacy status-view IDs; each additional projection gets its own ID.
		$id = 'status' === $context ? 'asqn-script-' . $item['term_id'] : 'asqn-' . $context . '-script-' . $item['term_id'];
		$bar->add_node( [ 'id' => $id, 'parent' => $parent, 'title' => $label, 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url( $item['parent'], $item['term_id'] ) ), 'meta' => [ 'title' => $this->description( $item ) ] ] );
	}
	public function statuses( $bar ): void {
		foreach ( [ 'active' => true, 'inactive' => false ] as $name => $status ) {
			$items = array_filter( $this->scripts, static function( $item ) use ( $status ) { return 'folder' !== $item['type'] && $status === $item['status']; } );
			$title = $status ? __( 'Active Scripts', 'advanced-scripts-quicknav' ) : __( 'Inactive Scripts', 'advanced-scripts-quicknav' );
			$bar->add_node( [ 'id' => 'asqn-' . $name, 'parent' => 'asqn-group-status', 'title' => esc_html( $title . ' (' . count( $items ) . ')' ), 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url() ) ] );
			$i = 0;
			foreach ( $items as $item ) { if ( $i++ >= ASQN_Config::limit() ) { break; } $this->script_node( $bar, $item, 'asqn-' . $name, 'status' ); }
			if ( count( $items ) > ASQN_Config::limit() ) { $this->more( $bar, 'asqn-' . $name, 0 ); }
		}
	}
	private function more( $bar, string $parent, int $folder ): void {
		$bar->add_node( [ 'id' => $parent . '-more', 'parent' => $parent, 'title' => esc_html__( 'View all in Advanced Scripts…', 'advanced-scripts-quicknav' ), 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url( $folder ) ) ] );
	}
	public function favorites( $bar ): void {
		$ids = ASQN_Favorites::ids( $this->scripts );
		$bar->add_group( [ 'id' => 'asqn-group-favorites', 'parent' => 'ddw-advscripts-quicknav' ] );
		$bar->add_node( [ 'id' => 'asqn-favorites', 'parent' => 'asqn-group-favorites', 'title' => esc_html__( '★ Favorites', 'advanced-scripts-quicknav' ), 'href' => esc_url( admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ) ] );
		foreach ( array_slice( $ids, 0, ASQN_Config::limit() ) as $id ) { $this->script_node( $bar, $this->scripts[$id], 'asqn-favorites', 'favorite' ); }
		$bar->add_node( [ 'id' => 'asqn-favorites-manage', 'parent' => 'asqn-favorites', 'title' => esc_html__( 'Manage favorites…', 'advanced-scripts-quicknav' ), 'href' => esc_url( admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ) ] );
	}
	public function folders( $bar ): void {
		$bar->add_node( [ 'id' => 'asqn-scripts-folders', 'parent' => 'asqn-group-folders', 'title' => esc_html__( 'Scripts by Folder', 'advanced-scripts-quicknav' ), 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url() ) ] );
		$visited = []; $budget = ASQN_Config::limit();
		$this->branch( $bar, 0, 'asqn-scripts-folders', $visited, $budget, 0 );
		// Keep orphaned items reachable without inventing an upstream folder hierarchy.
		foreach ( $this->scripts as $item ) {
			if ( isset( $visited[$item['term_id']] ) || $budget <= 0 ) { continue; }
			if ( 'folder' !== $item['type'] ) { $this->script_node( $bar, $item, 'asqn-scripts-folders', 'folder' ); $visited[$item['term_id']] = true; $budget--; }
		}
		if ( count( $visited ) < count( $this->scripts ) ) { $this->more( $bar, 'asqn-scripts-folders', 0 ); }
	}
	private function branch( $bar, int $folder, string $parent, array &$visited, int &$budget, int $depth ): void {
		if ( $depth >= 8 ) { $this->more( $bar, $parent, $folder ); return; }
		foreach ( $this->children[$folder] ?? [] as $id ) {
			if ( isset( $visited[$id] ) ) { continue; }
			if ( $budget <= 0 ) { $this->more( $bar, $parent, $folder ); break; }
			$visited[$id] = true; $budget--; $item = $this->scripts[$id];
			if ( 'folder' !== $item['type'] ) { $this->script_node( $bar, $item, $parent, 'folder' ); continue; }
			$node = 'asqn-folder-' . $id;
			$label = '▸ ' . $item['title'] . ( $item['status'] ? '' : ' · ' . __( 'inactive', 'advanced-scripts-quicknav' ) );
			$bar->add_node( [ 'id' => $node, 'parent' => $parent, 'title' => esc_html( $label ), 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url( $id ) ), 'meta' => [ 'title' => $this->description( $item ) ] ] );
			$bar->add_node( [ 'id' => $node . '-new', 'parent' => $node, 'title' => esc_html__( 'Add script here…', 'advanced-scripts-quicknav' ), 'href' => esc_url( ASQN_Advanced_Scripts_Adapter::url( $id, 0 ) ) ] );
			$this->branch( $bar, $id, $node, $visited, $budget, $depth + 1 );
		}
	}
}
