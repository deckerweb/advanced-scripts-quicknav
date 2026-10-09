<?php
/** Format the small Markdown subset used in deckerweb release notes. */
namespace Deckerweb\AdvancedScriptsQuickNav;
defined( 'ABSPATH' ) || exit;
/** Convert a bounded release-note subset into escaped WordPress markup. */
final class ReleaseNotes {
	/**
	 * Convert escaped release content into bounded structured HTML.
	 *
	 * @param string $source Escaped release notes or candidate package directory.
	 * @param string $locale Requested WordPress locale.
	 * @return string Escaped structured HTML for the supplied release content.
	 */
	public static function render( string $source, string $locale ): string {
		if ( strlen( $source ) > 262144 ) { return $source; }
		$text = html_entity_decode( strip_tags( preg_replace( '~<br\s*/?>|</p>~i', "\n", $source ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		// Keep one language only when both known editions are present.
		if ( preg_match( '/^## English\s*\R(.*?)^## Deutsch\s*\R(.*)$/ms', $text, $languages ) ) {
			$text = preg_match( '/^de(?:_|$)/i', $locale ) ? $languages[2] : $languages[1];
		}
		$html = ''; $paragraph = []; $list = false;
		/**
		 * Append the accumulated escaped paragraph and reset its buffer.
		 * @return void Updates the local HTML and paragraph buffers by reference.
		 */
		$flush = static function() use ( &$html, &$paragraph ): void {
			if ( $paragraph ) { $html .= '<p>' . self::inline( implode( ' ', $paragraph ) ) . '</p>'; $paragraph = []; }
		};
		foreach ( preg_split( '/\R/', $text ) as $line ) {
			$line = trim( $line );
			if ( preg_match( '/^[*\-] (.+)$/', $line, $item ) ) {
				$flush(); if ( ! $list ) { $html .= '<ul>'; $list = true; }
				$body = self::inline( $item[1] );
				$body = preg_replace( '/^(New|Improved|Fixed|Misc|Neu|Verbessert|Behoben|Sonstiges):/', '<strong>$1:</strong>', $body );
				$html .= '<li>' . $body . '</li>'; continue;
			}
			if ( $list ) { $html .= '</ul>'; $list = false; }
			if ( '' === $line ) { $flush(); }
			elseif ( preg_match( '/^#{1,6}\s+(.+)$/', $line, $heading ) ) { $flush(); $html .= '<h3>' . self::inline( $heading[1] ) . '</h3>'; }
			else { $paragraph[] = $line; }
		}
		$flush(); if ( $list ) { $html .= '</ul>'; }
		return $html;
	}
	/**
	 * Escape source fragments before adding supported inline markup.
	 *
	 * @param string $text Source text to escape and format.
	 * @return string Escaped text with only supported generated inline tags.
	 */
	private static function inline( string $text ): string {
		$html = '';
		foreach ( preg_split( '/(`[^`]+`|\*\*[^*]+\*\*|\[[^\]\r\n]+\]\(https?:\/\/[^\s)]+\))/', $text, -1, PREG_SPLIT_DELIM_CAPTURE ) as $part ) {
			if ( preg_match( '/^`([^`]+)`$/', $part, $match ) ) { $html .= '<code>' . esc_html( $match[1] ) . '</code>'; }
			elseif ( preg_match( '/^\*\*([^*]+)\*\*$/', $part, $match ) ) { $html .= '<strong>' . esc_html( $match[1] ) . '</strong>'; }
			elseif ( preg_match( '/^\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)$/', $part, $match ) ) { $html .= '<a href="' . esc_attr( esc_url( $match[2] ) ) . '">' . esc_html( $match[1] ) . '</a>'; }
			else { $html .= esc_html( $part ); }
		}
		return $html;
	}
}
