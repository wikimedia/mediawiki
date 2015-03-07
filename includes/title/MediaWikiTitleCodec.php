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
 * @note Normalization and validation is applied while parsing, not when formatting.
 * It's possible to construct a TitleValue with an invalid title, and use MediaWikiTitleCodec
 * to generate an (invalid) title string from it. TitleValues should be constructed only
 * via parseTitle() or from a (semi)trusted source, such as the database.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
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
	 * @var string[]
	 */
	protected $localInterwikis;

	/**
	 * @param Language $language The language object to use for localizing namespace names.
	 * @param GenderCache $genderCache The gender cache for generating gendered namespace names
	 * @param string[]|string $localInterwikis
	 */
	public function __construct( Language $language, GenderCache $genderCache,
		$localInterwikis = array()
	) {
		$this->language = $language;
		$this->genderCache = $genderCache;
		$this->localInterwikis = (array)$localInterwikis;
	}

	/**
	 * @see TitleFormatter::getNamespaceName()
	 *
	 * @param int $namespace
	 * @param string $text
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string
	 */
	public function getNamespaceName( $namespace, $text ) {
		if ( $this->language->needsGenderDistinction() &&
			MWNamespace::hasGenderDistinction( $namespace )
		) {

			//NOTE: we are assuming here that the title text is a user name!
			$gender = $this->genderCache->getGenderOf( $text, __METHOD__ );
			$name = $this->language->getGenderNsText( $namespace, $gender );
		} else {
			$name = $this->language->getNsText( $namespace );
		}

		if ( $name === false ) {
			throw new InvalidArgumentException( 'Unknown namespace ID: ' . $namespace );
		}

		return $name;
	}

	/**
	 * @see TitleFormatter::formatTitle()
	 *
	 * @param int|bool $namespace The namespace ID (or false, if the namespace should be ignored)
	 * @param string $text The page title. Should be valid. Only minimal normalization is applied.
	 *        Underscores will be replaced.
	 * @param string $fragment The fragment name (may be empty).
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string
	 */
	public function formatTitle( $namespace, $text, $fragment = '' ) {
		if ( $namespace !== false ) {
			$namespace = $this->getNamespaceName( $namespace, $text );

			if ( $namespace !== '' ) {
				$text = $namespace . ':' . $text;
			}
		}

		if ( $fragment !== '' ) {
			$text = $text . '#' . $fragment;
		}

		$text = str_replace( '_', ' ', $text );

		return $text;
	}

	/**
	 * Parses the given text and constructs a TitleValue. Normalization
	 * is applied according to the rules appropriate for the form specified by $form.
	 *
	 * @param string $text The text to parse
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 *
	 * @throws MalformedTitleException
	 * @return TitleValue
	 */
	public function parseTitle( $text, $defaultNamespace ) {
		// NOTE: this is an ugly cludge that allows this class to share the
		// code for parsing with the old Title class. The parser code should
		// be refactored to avoid this.
		$parts = $this->splitTitleString( $text, $defaultNamespace );

		// Interwiki links are not supported by TitleValue
		if ( $parts['interwiki'] !== '' ) {
			throw new MalformedTitleException( 'Title must not contain an interwiki prefix: ' . $text );
		}

		// Relative fragment links are not supported by TitleValue
		if ( $parts['dbkey'] === '' ) {
			throw new MalformedTitleException( 'Title must not be empty: ' . $text );
		}

		return new TitleValue( $parts['namespace'], $parts['dbkey'], $parts['fragment'] );
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param TitleValue $title
	 *
	 * @return string $title->getText()
	 */
	public function getText( TitleValue $title ) {
		return $this->formatTitle( false, $title->getText(), '' );
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param TitleValue $title
	 *
	 * @return string
	 */
	public function getPrefixedText( TitleValue $title ) {
		return $this->formatTitle( $title->getNamespace(), $title->getText(), '' );
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param TitleValue $title
	 *
	 * @return string
	 */
	public function getFullText( TitleValue $title ) {
		return $this->formatTitle( $title->getNamespace(), $title->getText(), $title->getFragment() );
	}

	/**
	 * Normalizes and splits a title string.
	 *
	 * This function removes illegal characters, splits off the interwiki and
	 * namespace prefixes, sets the other forms, and canonicalizes
	 * everything.
	 *
	 * @todo this method is only exposed as a temporary measure to ease refactoring.
	 * It was copied with minimal changes from Title::secureAndSplit().
	 *
	 * @todo This method should be split up and an appropriate interface
	 * defined for use by the Title class.
	 *
	 * @param string $text
	 * @param int $defaultNamespace
	 *
	 * @throws MalformedTitleException If $text is not a valid title string.
	 * @return array A mapp with the fields 'interwiki', 'fragment', 'namespace',
	 *         'user_case_dbkey', and 'dbkey'.
	 */
	public function splitTitleString( $text, $defaultNamespace = NS_MAIN ) {
		$dbkey = str_replace( ' ', '_', $text );

		# Initialisation
		$parts = array(
			'interwiki' => '',
			'local_interwiki' => false,
			'fragment' => '',
			'namespace' => $defaultNamespace,
			'dbkey' => $dbkey,
			'user_case_dbkey' => $dbkey,
		);

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = preg_replace( '/\xE2\x80[\x8E\x8F\xAA-\xAE]/S', '', $dbkey );

		# Clean up whitespace
		# Note: use of the /u option on preg_replace here will cause
		# input with invalid UTF-8 sequences to be nullified out in PHP 5.2.x,
		# conveniently disabling them.
		$dbkey = preg_replace(
			'/[ _\xA0\x{1680}\x{180E}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u',
			'_',
			$dbkey
		);
		$dbkey = trim( $dbkey, '_' );

		if ( strpos( $dbkey, UtfNormal\Constants::UTF8_REPLACEMENT ) !== false ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			throw new MalformedTitleException( 'Bad UTF-8 sequences found in title: ' . $text );
		}

		$parts['dbkey'] = $dbkey;

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( $dbkey !== '' && ':' == $dbkey[0] ) {
			$parts['namespace'] = NS_MAIN;
			$dbkey = substr( $dbkey, 1 ); # remove the colon but continue processing
			$dbkey = trim( $dbkey, '_' ); # remove any subsequent whitespace
		}

		if ( $dbkey == '' ) {
			throw new MalformedTitleException( 'Empty title: ' . $text );
		}

		# Namespace or interwiki prefix
		$prefixRegexp = "/^(.+?)_*:_*(.*)$/S";
		do {
			$m = array();
			if ( preg_match( $prefixRegexp, $dbkey, $m ) ) {
				$p = $m[1];
				if ( ( $ns = $this->language->getNsIndex( $p ) ) !== false ) {
					# Ordinary namespace
					$dbkey = $m[2];
					$parts['namespace'] = $ns;
					# For Talk:X pages, check if X has a "namespace" prefix
					if ( $ns == NS_TALK && preg_match( $prefixRegexp, $dbkey, $x ) ) {
						if ( $this->language->getNsIndex( $x[1] ) ) {
							# Disallow Talk:File:x type titles...
							throw new MalformedTitleException( 'Bad namespace prefix: ' . $text );
						} elseif ( Interwiki::isValidInterwiki( $x[1] ) ) {
							//TODO: get rid of global state!
							# Disallow Talk:Interwiki:x type titles...
							throw new MalformedTitleException( 'Interwiki prefix found in title: ' . $text );
						}
					}
				} elseif ( Interwiki::isValidInterwiki( $p ) ) {
					# Interwiki link
					$dbkey = $m[2];
					$parts['interwiki'] = $this->language->lc( $p );

					# Redundant interwiki prefix to the local wiki
					foreach ( $this->localInterwikis as $localIW ) {
						if ( 0 == strcasecmp( $parts['interwiki'], $localIW ) ) {
							if ( $dbkey == '' ) {
								# Empty self-links should point to the Main Page, to ensure
								# compatibility with cross-wiki transclusions and the like.
								$mainPage = Title::newMainPage();
								return array(
									'interwiki' => $mainPage->getInterwiki(),
									'local_interwiki' => true,
									'fragment' => $mainPage->getFragment(),
									'namespace' => $mainPage->getNamespace(),
									'dbkey' => $mainPage->getDBkey(),
									'user_case_dbkey' => $mainPage->getUserCaseDBKey()
								);
							}
							$parts['interwiki'] = '';
							# local interwikis should behave like initial-colon links
							$parts['local_interwiki'] = true;

							# Do another namespace split...
							continue 2;
						}
					}

					# If there's an initial colon after the interwiki, that also
					# resets the default namespace
					if ( $dbkey !== '' && $dbkey[0] == ':' ) {
						$parts['namespace'] = NS_MAIN;
						$dbkey = substr( $dbkey, 1 );
					}
				}
				# If there's no recognized interwiki or namespace,
				# then let the colon expression be part of the title.
			}
			break;
		} while ( true );

		$fragment = strstr( $dbkey, '#' );
		if ( false !== $fragment ) {
			$parts['fragment'] = str_replace( '_', ' ', substr( $fragment, 1 ) );
			$dbkey = substr( $dbkey, 0, strlen( $dbkey ) - strlen( $fragment ) );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$dbkey = preg_replace( '/_*$/', '', $dbkey );
		}

		# Reject illegal characters.
		$rxTc = self::getTitleInvalidRegex();
		if ( preg_match( $rxTc, $dbkey ) ) {
			throw new MalformedTitleException( 'Illegal characters found in title: ' . $text );
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
			throw new MalformedTitleException( 'Bad title: ' . $text );
		}

		# Magic tilde sequences? Nu-uh!
		if ( strpos( $dbkey, '~~~' ) !== false ) {
			throw new MalformedTitleException( 'Bad title: ' . $text );
		}

		# Limit the size of titles to 255 bytes. This is typically the size of the
		# underlying database field. We make an exception for special pages, which
		# don't need to be stored in the database, and may edge over 255 bytes due
		# to subpage syntax for long titles, e.g. [[Special:Block/Long name]]
		if (
			( $parts['namespace'] != NS_SPECIAL && strlen( $dbkey ) > 255 )
			|| strlen( $dbkey ) > 512
		) {
			throw new MalformedTitleException( 'Title too long: ' . substr( $dbkey, 0, 255 ) . '...' );
		}

		# Normally, all wiki links are forced to have an initial capital letter so [[foo]]
		# and [[Foo]] point to the same place.  Don't force it for interwikis, since the
		# other site might be case-sensitive.
		$parts['user_case_dbkey'] = $dbkey;
		if ( $parts['interwiki'] === '' ) {
			$dbkey = Title::capitalize( $dbkey, $parts['namespace'] );
		}

		# Can't make a link to a namespace alone... "empty" local links can only be
		# self-links with a fragment identifier.
		if ( $dbkey == '' && $parts['interwiki'] === '' ) {
			if ( $parts['namespace'] != NS_MAIN ) {
				throw new MalformedTitleException( 'Empty title: ' . $text );
			}
		}

		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		if ( $parts['namespace'] == NS_USER || $parts['namespace'] == NS_USER_TALK ) {
			$dbkey = IP::sanitizeIP( $dbkey );
		}

		// Any remaining initial :s are illegal.
		if ( $dbkey !== '' && ':' == $dbkey[0] ) {
			throw new MalformedTitleException( 'Title must not start with a colon: ' . $text );
		}

		# Fill fields
		$parts['dbkey'] = $dbkey;

		return $parts;
	}

	/**
	 * Returns a simple regex that will match on characters and sequences invalid in titles.
	 * Note that this doesn't pick up many things that could be wrong with titles, but that
	 * replacing this regex with something valid will make many titles valid.
	 * Previously Title::getTitleInvalidRegex()
	 *
	 * @return string Regex string
	 * @since 1.25
	 */
	public static function getTitleInvalidRegex() {
		static $rxTc = false;
		if ( !$rxTc ) {
			# Matching titles will be held as illegal.
			$rxTc = '/' .
				# Any character not allowed is forbidden...
				'[^' . Title::legalChars() . ']' .
				# URL percent encoding sequences interfere with the ability
				# to round-trip titles -- you can't link to them consistently.
				'|%[0-9A-Fa-f]{2}' .
				# XML/HTML character references produce similar issues.
				'|&[A-Za-z0-9\x80-\xff]+;' .
				'|&#[0-9]+;' .
				'|&#x[0-9A-Fa-f]+;' .
				'/S';
		}

		return $rxTc;
	}
}
