<?php
/**
 * @ingroup Language
 */
class LanguageYue extends Language {
	function stripForSearch( $string ) {
		wfProfileIn( __METHOD__ );
		global $wgSearchType;

		$s = $string;

		// Double-width roman characters: ff00-ff5f ~= 0020-007f
		$s = preg_replace( '/\xef\xbc([\x80-\xbf])/e', 'chr((ord("$1") & 0x3f) + 0x20)', $s );
		$s = preg_replace( '/\xef\xbd([\x80-\x99])/e', 'chr((ord("$1") & 0x3f) + 0x60)', $s );

		if ( $wgSearchType != 'LuceneSearch' ) {
			// eventually this should be a word segmentation;
			// for now just treat each character as a word.
			// Not for LuceneSearch, because LSearch will
			// split the text to words itself.
			// @todo Fixme: only do this for Han characters...
			$s = preg_replace(
					"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
					" $1 ", $s);
			$s = preg_replace( '/ +/', ' ', $s );
		}

		$s = trim( $s );

		// Do general case folding and UTF-8 armoring
		$s = parent::stripForSearch( $s );
		wfProfileOut( __METHOD__ );
		return $s;
	}
}
