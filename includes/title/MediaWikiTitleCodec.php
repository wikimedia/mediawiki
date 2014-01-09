<?php
/**
 * A codec for %MediaWiki page titles.
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
 * A codec for %MediaWiki page titles.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class MediaWikiTitleCodec implements TitleFormatter, TitleParser {

	/**
	 * @var Language
	 */
	protected $language;

	/**
	 * @var GenderCache
	 */
	protected $genderCache;

	/**
	 * @param Language $language the language object to use for localizing namespace names.
	 * @param GenderCache $genderCache the gender cache for generating gendered namespace names
	 */
	public function __construct( Language $language, GenderCache $genderCache ) {
		$this->language = $language;
		$this->genderCache = $genderCache;
	}

	/**
	 * Converts to the given form. This does not apply normalization, but
	 * only changes the representation of whitespace between ' ' and '_'.
	 *
	 * @param string $text
	 * @param int $form use the TitleValue::XXX_FORM constants
	 *
	 * @return string
	 */
	private function convert( $text, $form ) {
		if ( $form === TitleValue::DBKEY_FORM ) {
			return str_replace( ' ', '_', $text );
		}

		if ( $form === TitleValue::TITLE_FORM ) {
			return str_replace( '_', ' ', $text );
		}

		return $text;
	}

	/**
	 * Applies minimal sanitization on the given string, such as cleanup of broken unicode,
	 * and trimming of whitespace.
	 *
	 * @param string $text
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	private function sanitize( $text ) {
		if ( !is_string( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string' );
		}

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$text = preg_replace(
			'/\xE2\x80[\x8E\x8F\xAA-\xAE]/S',
			'',
			$text
		);

		# Clean up whitespace
		# Note: use of the /u option on preg_replace here will cause
		# input with invalid UTF-8 sequences to be nullified out in PHP 5.2.x,
		# conveniently disabling them.
		$text = preg_replace(
			'/[\xA0\x{1680}\x{180E}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u',
			'_',
			$text
		);

		//NOTE: don't trim '_' here, since that's not correct for sections.
		$text = trim( $text, ' ' );

		if ( strpos( $text, UTF8_REPLACEMENT ) !== false ) {
			throw new InvalidArgumentException(
				'Title contains illegal UTF-8 sequences or forbidden Unicode chars'
			);
		}

		return $text;
	}

	/**
	 * Applies the appropriate normalization for the requested form.
	 * This includes trimming, fixing some broken UTF-8, and other sanitizing.
	 *
	 * @note: The normalization applied may depend on the namespace. E.g. titles
	 *        in the user namespace have IP address normalization applied.
	 * @note: no capitalization is applied for UNKNOWN_FORM.
	 *
	 * @param string $text
	 * @param int $namespace the namespace for normalization context
	 * @param int $form use the TitleValue::XXX_FORM constants
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	private function normalizeTitleText( $text, $namespace, $form ) {
		if ( !is_int( $namespace ) ) {
			// avoid mixing up $namespace and $text
			throw new InvalidArgumentException( '$namespace must be an int (or false)' );
		}

		$text = $this->sanitize( $text );

		// since we know we are dealing with title text, we can now strip underscores too.
		$text = trim( $text, '_ ' );

		if ( strpos( $text, UTF8_REPLACEMENT ) !== false ) {
			throw new InvalidArgumentException(
				'Title contains illegal UTF-8 sequences or forbidden Unicode chars'
			);
		}

		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$text = IP::sanitizeIP( $text );
			//TODO: if this isn't a valid IPv6 address, it must not start with a colon!
		}

		if ( $form !== TitleValue::UNKNOWN_FORM ) {
			// Capitalize if appropriate
			// TODO: avoid using MWNamespace, global state is evil!
			if ( $namespace !== false && MWNamespace::isCapitalized( $namespace ) ) {
				$text =  $this->language->ucfirst( $text );
			}
		}

		$text = $this->convert( $text, $form );
		return $text;
	}

	/**
	 * @param TitleValue $title
	 * @param int $parts use the TitleFormatter::INCLUDE_XXX constants
	 * @param int $form use the TitleValue::XXX_FORM constants
	 *
	 * @return string
	 */
	private function compose( TitleValue $title, $parts, $form ) {
		$text = $this->convert( $title->getText(), $form );

		if ( ( $parts & TitleFormatter::INCLUDE_NAMESPACE ) !== 0 ) {
			$namespace = $this->getNamespaceName( $title );
			$namespace = $this->convert( $namespace, $form );

			if ( $namespace !== '' ) {
				$text = $namespace . ':' . $text;
			}
		}

		if ( ( $parts & TitleFormatter::INCLUDE_SECTION ) !== 0 ) {
			$section = $this->convert( $title->getSection(), $form );

			if ( $section !== '' ) {
				$text = $text . '#' . $section;
			}
		}

		return $text;
	}

	/**
	 * Returns the name of the namespace of the given title.
	 * @note This takes into account gender sensitive namespace names.
	 *
	 * @param TitleValue $title
	 *
	 * @return String
	 */
	public function getNamespaceName( TitleValue $title ) {
		$ns = $title->getNamespace();
		$titleText = $title->getText();

		if ( $this->language->needsGenderDistinction() &&
			MWNamespace::hasGenderDistinction( $ns ) ) {

			//NOTE: we are assuming here that the title text is a user name!
			$gender = $this->genderCache->getGenderOf( $titleText, __METHOD__ );
			return $this->language->getGenderNsText( $ns, $gender );
		} else {
			return $this->language->getNsText( $ns );
		}
	}

	/**
	 * Returns a TitleValue normalized for the given target form.
	 *
	 * @param TitleValue $title the title to normalize.
	 * @param int $form use the TitleValue::XXX_FORM constants
	 *
	 * @throws InvalidArgumentException if $title could not be normalized
	 * @return TitleValue
	 */
	private function normalizeTitle( TitleValue $title, $form ) {
		if ( $title->getForm() === $form || $form === TitleValue::UNKNOWN_FORM ) {
			return $title;
		}

		$text = $this->normalizeTitleText( $title->getText(), $title->getNamespace(), $form );

		$section = $title->getSection();

		//NOTE: sections should be converted to DB form, but never to display form,
		//      because they may contain literal underscores.
		if ( $form === TitleValue::DBKEY_FORM ) {
			$section = $this->convert( $section, TitleValue::DBKEY_FORM );
		}

		return new TitleValue(
			$form,
			$title->getNamespace(),
			$text,
			$section
		);
	}

	/**
	 * Returns a TitleValue normalized for use in the database.
	 *
	 * @param TitleValue $title the title to normalize.
	 *
	 * @throws InvalidArgumentException if $title could not be normalized
	 * @return TitleValue
	 */
	public function normalizeForDisplay( TitleValue $title ) {
		return $this->normalizeTitle( $title, TitleValue::TITLE_FORM );
	}

	/**
	 * Returns a TitleValue normalized for use in the database.
	 *
	 * @param TitleValue $title the title to normalize.
	 *
	 * @throws InvalidArgumentException if $title could not be normalized
	 * @return TitleValue
	 */
	public function normalizeForDatabase( TitleValue $title ) {
		return $this->normalizeTitle( $title, TitleValue::DBKEY_FORM );
	}

	/**
	 * Returns the title formatted for display.
	 * Per default, this includes the namespace but not the section.
	 *
	 * @param TitleValue $title the title to format
	 * @param int $parts which parts to show, use the INCLUDE_XXX constants.
	 *
	 * @return string
	 */
	public function formatForDisplay( TitleValue $title, $parts = self::INCLUDE_NAMESPACE ) {
		$title = $this->normalizeForDisplay( $title );
		return $this->compose( $title, $parts, TitleValue::TITLE_FORM );
	}

	/**
	 * Returns the title formatted for use in the database.
	 * Per default, this does not include namespace or section.
	 *
	 * @param TitleValue $title the title to format
	 * @param int $parts which parts to show, use the INCLUDE_XXX constants.
	 *
	 * @return string
	 */
	public function formatForDatabase( TitleValue $title, $parts = self::INCLUDE_BASE ) {
		$title = $this->normalizeForDatabase( $title );
		return $this->compose( $title, $parts, TitleValue::DBKEY_FORM );
	}

	/**
	 * Parses the given text and constructs a TitleValue. Normalization
	 * is applied according to the rules appropriate for the form specified by $form.
	 *
	 * @param string $text the text to parse
	 * @param int $defaultNamespace namespace to assume per default (usually NS_MAIN)
	 * @param int $form the desired form of the title, use the TitleValue::XXX_FORM constants.
	 *
	 * @throws InvalidArgumentException
	 * @return TitleValue
	 */
	public function parseTitle( $text, $defaultNamespace, $form ) {
		# Initialisation
		$namespace = $defaultNamespace;

		//NOTE: this does not apply capitalization or space/underscore normalization.
		$dbkey = $this->sanitize( $text );
		$dbkey = trim( $dbkey, '_ ' );

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( $dbkey !== '' && ':' == $dbkey[0] ) {
			$namespace = NS_MAIN;
			$dbkey = substr( $dbkey, 1 ); # remove the colon but continue processing
			$dbkey = trim( $dbkey, '_ ' ); # remove any subsequent whitespace
		}

		if ( $dbkey == '' ) {
			throw new InvalidArgumentException( 'Empty title' );
		}

		# Namespace or interwiki prefix
		$firstPass = true;
		$prefixRegexp = "/^(.+?)[_ ]*:[_ ]*(.*)$/S";
		do {
			$m = array();
			if ( preg_match( $prefixRegexp, $dbkey, $m ) ) {
				$p = $m[1];
				if ( $firstPass && ( $ns = $this->language->getNsIndex( $p ) ) !== false ) {
					# Ordinary namespace
					$dbkey = $m[2];
					$namespace = $ns;
					# For Talk:X pages, check if X has a "namespace" prefix
					if ( $ns == NS_TALK && preg_match( $prefixRegexp, $dbkey, $x ) ) {
						if ( $this->language->getNsIndex( $x[1] ) ) {
							# Disallow Talk:File:x type titles...
							throw new InvalidArgumentException( 'Unexpected namespace found in title: ' . $dbkey );
						} else{
							//TODO: avoid using Interwiki and $wgLocalInterwiki, global state is evil!
							if ( Interwiki::isValidInterwiki( $x[1] ) ) {
								# Disallow Talk:Interwiki:x type titles...
								throw new InvalidArgumentException( 'Interwiki prefix found in title: ' . $dbkey );
							}
						}
					}

					// continue to detect interwiki prefixes following the namespace
					$firstPass = false;
					continue;
				} else {
					//TODO: avoid using Interwiki and $wgLocalInterwiki, global state is evil!
					if ( Interwiki::isValidInterwiki( $p ) || $p === $GLOBALS['wgLocalInterwiki'] ) {
						// interwiki prefixes are not allowed as part of titles.
						throw new InvalidArgumentException( 'Interwiki prefix found in title: ' . $dbkey );
					}
				}
				// If there's no recognized interwiki or namespace,
				// then let the colon expression be part of the title.
			}

			break;
		} while ( true );

		$fragment = strstr( $dbkey, '#' );
		if ( false === $fragment ) {
			$fragment = '';
		} else {
			$fragment =  $this->sanitize( substr( $fragment, 1 ) );
			$dbkey = substr( $dbkey, 0, strlen( $dbkey ) - strlen( $fragment ) -1 );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$dbkey = preg_replace( '/[_ ]*$/', '', $dbkey );
		}

		# Reject illegal characters.
		$rxTc = Title::getTitleInvalidRegex();
		if ( preg_match( $rxTc, $dbkey ) ) {
			throw new InvalidArgumentException( 'Bad characters in title: ' . $dbkey );
		}

		# Pages with "/./" or "/../" appearing in the URLs will often be un-
		# reachable due to the way web browsers deal with 'relative' URLs.
		# Also, they conflict with subpage syntax.  Forbid them explicitly.
		if (
			strpos( $dbkey, '.' ) !== false &&
			(
				$dbkey === '.' || $dbkey === '..' ||
				strpos( $dbkey, './' ) === 0 ||
				strpos( $dbkey, '../' ) === 0 ||
				strpos( $dbkey, '/./' ) !== false ||
				strpos( $dbkey, '/../' ) !== false ||
				substr( $dbkey, -2 ) == '/.' ||
				substr( $dbkey, -3 ) == '/..'
			)
		) {
			throw new InvalidArgumentException( 'Malformed title: ' . $dbkey );
		}

		# Magic tilde sequences? Nu-uh!
		if ( strpos( $dbkey, '~~~' ) !== false ) {
			throw new InvalidArgumentException( 'Malformed title: ' . $dbkey );
		}

		# Limit the size of titles to 255 bytes. This is typically the size of the
		# underlying database field. We make an exception for special pages, which
		# don't need to be stored in the database, and may edge over 255 bytes due
		# to subpage syntax for long titles, e.g. [[Special:Block/Long name]]
		if (
			( $namespace != NS_SPECIAL && strlen( $dbkey ) > 255 )
			|| strlen( $dbkey ) > 512
		) {
			throw new InvalidArgumentException( 'Title too long: ' . $dbkey );
		}

		# Can't make a link to a namespace alone... "empty" local links can only be
		# self-links with a fragment identifier.
		if ( $dbkey == '' && $namespace != NS_MAIN ) {
			throw new InvalidArgumentException( 'Empty title' );
		}

		$dbkey = $this->normalizeTitleText( $dbkey, $namespace, $form );

		// Any remaining initial :s are illegal
		if ( $dbkey !== '' && ':' == $dbkey[0]  ) {
			throw new InvalidArgumentException( 'Malformed title: ' . $dbkey );
		}

		return new TitleValue(
			$form,
			$namespace,
			$dbkey,
			$fragment
		);
	}
}
