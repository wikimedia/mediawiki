<?php
/**
 * A title normalization service for %MediaWiki.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * A title normalization service for MediaWiki.
 *
 * This is designed to encapsulate the conventions for the title forms to be used in the
 * database, in links, and in wikitext.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class TitleNormalizer {

	/**
	 * Returns the title in the form conventionally used in human readable text.
	 *
	 * @param string $titleText
	 * @param string $safe flag string for safe title text; set to 'safe' to indicate
	 *        that the title is known to be safe, e.g. when it comes from the database.
	 *
	 * @return string
	 * @throws InvalidArgumentException if the title is invalid and cannot be fixed.
	 */
	public function getTextForm( TitleValue $title, $safe = '' ) {
		if ( $safe !== 'safe' ) {
			$titleText = $this->makeSafe( $titleText );
		}

		return str_replace( '_', ' ', $titleText );
	}

	/**
	 * Returns the title in the form conventionally used in the database.
	 * The result is not yet escaped for use in queries.
	 *
	 * @param string $titleText
	 * @param string $safe flag string for safe title text; set to 'safe' to indicate
	 *        that the title is known to be safe, e.g. when it comes from the database.
	 *
	 * @return string
	 * @throws InvalidArgumentException if the title is invalid and cannot be fixed.
	 */
	public function getDBForm( $titleText, $safe = '' ) {
		if ( $safe !== 'safe' ) {
			$titleText = $this->makeSafe( $titleText );
		}

		return str_replace( ' ', '_', $titleText );
	}

	/**
	 * Returns the title in the form conventionally used in URLs.
	 * The result is not yet URLEncoded.
	 *
	 * @param string $titleText
	 * @param string $safe flag string for safe title text; set to 'safe' to indicate
	 *        that the title is known to be safe, e.g. when it comes from the database.
	 *
	 * @return string
	 * @throws InvalidArgumentException if the title is invalid and cannot be fixed.
	 */
	public function getURLForm( $titleText, $safe = '' ) {
		return $this->getDBForm( $titleText, $safe );
	}

	/**
	 * @param $titleText
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException if the title is invalid and cannot be fixed.
	 */
	public function makeSafe( $titleText ) {
		$dbkey = str_replace( ' ', '_', $titleText );

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = preg_replace( '/\xE2\x80[\x8E\x8F\xAA-\xAE]/S', '', $dbkey );

		# Clean up whitespace
		# Note: use of the /u option on preg_replace here will cause
		# input with invalid UTF-8 sequences to be nullified out in PHP 5.2.x,
		# conveniently disabling them.
		$dbkey = preg_replace( '/[ _\xA0\x{1680}\x{180E}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '_', $dbkey );
		$dbkey = trim( $dbkey, '_' );

		if ( strpos( $dbkey, UTF8_REPLACEMENT ) !== false ) {
			throw new InvalidArgumentException( 'Title contained illegal UTF-8 sequences or forbidden Unicode chars.' );
		}


		# Limit the size of titles to 255 bytes. This is typically the size of the
		# underlying database field. We make an exception for special pages, which
		# don't need to be stored in the database, and may edge over 255 bytes due
		# to subpage syntax for long titles, e.g. [[Special:Block/Long name]]
		if (
			( $this->namespace != NS_SPECIAL && strlen( $dbkey ) > 255 )
			|| strlen( $dbkey ) > 512
		) {
			return false;
		}

		# Normally, all wiki links are forced to have an initial capital letter so [[foo]]
		# and [[Foo]] point to the same place.  Don't force it for interwikis, since the
		# other site might be case-sensitive.
		$this->mUserCaseDBKey = $dbkey;
		if ( !$this->isExternal() ) {
			$dbkey = self::capitalize( $dbkey, $this->mNamespace );
		}

		# Can't make a link to a namespace alone... "empty" local links can only be
		# self-links with a fragment identifier.
		if ( $dbkey == '' && !$this->isExternal() && $this->mNamespace != NS_MAIN ) {
			return false;
		}

		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		$dbkey = ( $this->mNamespace == NS_USER || $this->mNamespace == NS_USER_TALK )
			? IP::sanitizeIP( $dbkey )
			: $dbkey;

		// Any remaining initial :s are illegal.
		if ( $dbkey !== '' && ':' == $dbkey[0] ) {
			return false;
		}

		# Fill fields
		$this->mDbkeyform = $dbkey;
		$this->mUrlform = wfUrlencode( $dbkey );

		$this->mTextform = str_replace( '_', ' ', $dbkey );

		return true;
	}

	/**
	 * Capitalize a text string for a title if it belongs to a namespace that capitalizes
	 *
	 * @param string $text containing title to capitalize
	 * @param int $ns namespace index, defaults to NS_MAIN
	 * @return String containing capitalized title
	 */
	public static function capitalize( $text, $ns = NS_MAIN ) {
		global $wgContLang;

		if ( MWNamespace::isCapitalized( $ns ) ) {
			return $wgContLang->ucfirst( $text );
		} else {
			return $text;
		}
	}
}
 